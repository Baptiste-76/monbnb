<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BookingRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Booking
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="bookings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $booker;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Ad", inversedBy="bookings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ad;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\Date(message="Le format de la date d'arrivée n'est pas valide")
     * @Assert\NotNull(message="N'oubliez pas de nous dire quand vous arriverez !")
     * @Assert\GreaterThan(
     *  "today", 
     *  message="La date d'arrivée doit être ultérieure à la date d'aujourd'hui !", 
     *  groups={"front"}
     * )
     */
    private $startDate;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\Date(message="Le format de la date de départ n'est pas valide")
     * @Assert\GreaterThan(propertyPath="startDate", message="La date de départ doit être ultérieure à la date d'arrivée !")
     */
    private $endDate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="float")
     */
    private $amount;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $comment;

    /**
     * Callback appelé à chaque fois qu'on crée une réservation ou qu'un admin la modifie
     * 
     * @ORM\PrePersist
     * @ORM\PreUpdate
     *
     * @return Response
     */
    public function prePersist() {
        if (empty($this->createdAt)) {
            $this->createdAt = new \DateTime();
        }

        if (empty($this->amount)) {
            $this->amount = $this->ad->getPrice() * $this->getDuration();
        }
    }

    public function getDuration() {
        $diff = $this->endDate->diff($this->startDate);
        return $diff->days;
    }

    public function isBookableDates() {
        // On récupère les dates indisponibles
        $notAvailableDays = $this->ad->getNotAvailableDays();

        // On récupère les dates choisies
        $bookingDays = $this->getDays();

        // On crée une fonction qui permet de transformer les dates Timestamp en chaine de caractères
        $formatDay = function($day) {
            return $day->format('Y-m-d');
        };

        // On applique cette fonction aux dates choisies dans la réservation en cours
        $days = array_map($formatDay, $bookingDays);

        // On applique aussi cette fonctions aux dates indisponibles
        $notAvailable = array_map($formatDay, $notAvailableDays);

        foreach ($days as $day) {
            if (array_search($day, $notAvailable) !== false) {
                return false;
            }
        }

        return true;
    }

    /**
     * Permet de récupérer un tableau des journées qui correspondent à la réservation en cours
     *
     * @return array Un tableau d'objets DateTime représentant les jours de la réservation en cours
     */
    public function getDays() {
        $resultat = range(
            $this->startDate->getTimestamp(),
            $this->endDate->getTimestamp(),
            24 *60 * 60
        );

        $days = array_map(function($dayTimestamp) {
            return new \DateTime(date('Y-m-d', $dayTimestamp));
        }, $resultat);

        return $days;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBooker(): ?User
    {
        return $this->booker;
    }

    public function setBooker(?User $booker): self
    {
        $this->booker = $booker;

        return $this;
    }

    public function getAd(): ?Ad
    {
        return $this->ad;
    }

    public function setAd(?Ad $ad): self
    {
        $this->ad = $ad;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate($startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate($endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment($comment): self
    {
        $this->comment = $comment;

        return $this;
    }
}
