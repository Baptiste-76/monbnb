<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AdminUserType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminUserController extends AbstractController
{
    /**
     * @Route("/admin/users", name="admin_users_index")
     */
    public function index(UserRepository $repo)
    {
        return $this->render('admin/user/index.html.twig', [
            'users' => $repo->findAll()
        ]);
    }

    /**
     * Permet de modifier les informations d'un utilisateur
     *
     * @Route("/admin/users/{id}/edit", name="admin_users_edit")
     * 
     * @return Response
     */
    public function edit(User $user, Request $request, ObjectManager $manager) {
        $form = $this->createForm(AdminUserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setSlug("");

            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                "L'utilisateur n° " . $user->getId() . " (" . $user->getFullName() . ") a bien été modifié !"
            );

            return $this->redirectToRoute("admin_users_index");
        }

        return $this->render("admin/user/edit.html.twig", [
            'user' => $user,
            'form' => $form->createView()
        ]);
    }
}
