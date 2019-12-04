<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Form\AdminBookingType;
use App\Repository\BookingRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminBookingController extends AbstractController
{
    /**
     * @Route("/admin/bookings", name="admin_bookings_index")
     */
    public function index(BookingRepository $repo)
    {
        return $this->render('admin/booking/index.html.twig', [
            'bookings' => $repo->findAll()
        ]);
    }

    /**
     * Permet d'éditer une réservation
     *
     * @Route("/admin/bookings/{id}/edit", name="admin_bookings_edit")
     * 
     * @return Response
     */
    public function edit(Booking $booking, Request $request, ObjectManager $manager) {
        $form = $this->createForm(AdminBookingType::class, $booking);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Grâce à l'événement de cycle de vie (PreUpdate), la fonction prePersist() de l'entité "Booking" va être appelée automatiquement et puisque le montant est à 0 (= empty), le montant va être recalculé
            $booking->setAmount(0);

            $manager->persist($booking);
            $manager->flush();

            $this->addFlash(
                'success',
                "La réservation n° " . $booking->getId() . " a bien été modifiée !"
            );

            return $this->redirectToRoute("admin_bookings_index");
        }

        return $this->render("admin/booking/edit.html.twig", [
            'form' => $form->createView(),
            'booking' => $booking
        ]);
    }

    /**
     * Permet de supprimer une réservation
     * 
     * @Route("/admin/bookings/{id}/delete", name="admin_bookings_delete")
     *
     * @return Response
     */
    public function delete(Booking $booking, ObjectManager $manager) {
        $bookingId = $booking->getId();

        $manager->remove($booking);
        $manager->flush();

        $this->addFlash(
            'success',
            "La réservation n° " . $bookingId . " a bien été supprimée !"
        );

        return $this->redirectToRoute("admin_bookings_index");
    }
}
