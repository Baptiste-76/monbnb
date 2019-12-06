<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AdType;
use App\Service\PaginationService;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminAdController extends AbstractController
{
    /**
     * {page} signifie que nous pouvons avoir une valeur aprés /ads, <\d+> signifie qu'on contraint cette valeur à être un nombre, ? signifie que ce paramètre est optionnel afin que /admin/ads soit toujours accepté
     * et 1 signifie que par défaut $page = 1
     * @Route("/admin/ads/{page<\d+>?1}", name="admin_ads_index")
     */
    public function index($page, PaginationService $pagination)
    {
        $pagination->setEntityClass(Ad::class)
                   ->setCurrentPage($page);

        return $this->render('admin/ad/index.html.twig', [
           'pagination' => $pagination
        ]);
    }

    /**
     * Permet d'afficher le formulaire d'édition d'une annonce
     *
     * @Route("admin/ads/{id}/edit", name="admin_ads_edit")
     * 
     * @param Ad $ad
     * @param Request $request
     * @param ObjectManager $manager
     * @return Response
     */
    public function edit(Ad $ad, Request $request, ObjectManager $manager)  {
        $form = $this->createForm(AdType::class, $ad);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($ad);
            $manager->flush();

            $this->addFlash(
                'success',
                'Les modifications sur l\'annonce <strong>' . $ad->getTitle() . ' </strong>ont bien été enregistrées !'
            );
        }

        return $this->render('admin/ad/edit.html.twig', [
            'ad' => $ad,
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet de supprimer une annonce
     *
     * @Route("admin/ads/{id}/delete", name="admin_ads_delete")
     * 
     * @param Ad $ad
     * @param ObjectManager $manager
     * @return Response
     */
    public function delete(Ad $ad, ObjectManager $manager) {
        if (count($ad->getBookings()) > 0) {
            $this->addFlash(
                'warning',
                'Attention, l\'annonce <strong>' . $ad->getTitle() . ' </strong> possède des réservations ! Vous ne pouvez pas la supprimer !'
            );
        } else {
            $manager->remove($ad);
            $manager->flush();
    
            $this->addFlash(
                'success',
                'L\'annonce <strong>' . $ad->getTitle() . ' </strong> a bien été supprimée !'
            );
        }

        return $this->redirectToRoute('admin_ads_index');
    }
}
