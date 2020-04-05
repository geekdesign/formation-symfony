<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\AdminCommentType;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminCommentController extends AbstractController
{
    /**
     * Affiche la liste des commentaires 
     * 
     * @Route("/admin/comment", name="admin_comment")
     * 
     * @param CommentRepository $repo
     */
    public function index(CommentRepository $repo)
    {
        return $this->render('admin/comment/index.html.twig', [
            'comments' => $repo->findAll(),
        ]);
    }

    /**
    * Permet l'édition de commentaire
    * 
    * @Route("/admin/comment/{id}/edit", name="admin_comment_edit")
    */
    public function edit(Comment $comment, Request $request, EntityManagerInterface $manager)
    {
        $form = $this->createForm(AdminCommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($comment);
            $manager->flush();

            $this->addFlash(
                'success',
                "Le commentaire bien été mise à jour!"
            );
        }

         return $this->render('admin/comment/edit.html.twig', [
            'comment' => $comment,
            'form' => $form->createView(),
        ]);
    }

    /**
    * @Route("/admin/comment/{id}/delete", name="admin_comment_delete")
    */
    public function delete(Comment $comment, EntityManagerInterface $manager)
    {
        $manager->remove($comment);
        $manager->flush();
    
        $this->addFlash(
            'success',
            "Le commentaire <strong>{$comment->getId()}</strong> a bien été supprimée !"
        );
        return $this->redirectToRoute('admin_comment');
    }
}
