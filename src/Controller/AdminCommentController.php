<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\AdminCommentType;
use App\Service\PaginationService;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminCommentController extends AbstractController
{
    /**
     * @Route("/admin/comments/{page<\d+>?1}", name="admin_comments_index")
     */
    public function index(PaginationService $pagination, $page)
    {
        $pagination->setEntityClass(Comment::class)
                   ->setCurrentPage($page);

        return $this->render('admin/comment/index.html.twig', [
            'pagination' => $pagination
        ]);
    }

    /**
     * Permet d'afficher le formulaire de modification d'un commentaire par un administrateur
     *
     * @Route("admin/comments/{id}/edit", name="admin_comments_edit")
     * 
     * @param Comment $comment
     * @param Request $request
     * @param ObjectManager $manager
     * @return Response
     */
    public function edit(Comment $comment, Request $request, ObjectManager $manager) 
    {
        $form = $this->createForm(AdminCommentType::class, $comment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setContent("<strong>Ce commentaire a été modifié par un administrateur :</strong> " . $comment->getContent());

            $manager->persist($comment);
            $manager->flush();

            $this->addFlash(
                'success',
                'Les modifications du commentaire n° ' . $comment->getId() . ' ont bien été enregistrées !'
            );
        }

        return $this->render("admin/comment/edit.html.twig", [
            'comment' => $comment,
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet à un administrateur de supprimer un commentaire
     *
     * @Route("admin/comment/{id}/delete", name="admin_comments_delete")
     * 
     * @param ObjectManager $manager
     * @param Comment $comment
     * @return Response
     */
    public function delete(ObjectManager $manager, Comment $comment) 
    {
        $manager->remove($comment);
        $manager->flush();

        $this->addFlash(
            'success',
            "Le commentaire de <strong>" . $comment->getAuthor()->getFullName() . " </strong>a bien été supprimé !"
        );

        return $this->redirectToRoute("admin_comments_index");
    }
}
