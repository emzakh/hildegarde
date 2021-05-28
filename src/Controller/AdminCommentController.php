<?php

namespace App\Controller;

use App\Entity\Commentaires;
use App\Form\CommentType;
use App\Repository\CommentairesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminCommentController extends AbstractController
{
    /**
     * @Route("/admin/comments", name="admin_comments_index")
     */
    public function index(CommentairesRepository  $repo): Response
    {
        return $this->render('admin/comment/index.html.twig', [
            'comments' => $repo->findAll()
        ]);
    }

    /**
     * Permet de modifier un commentaire
     * @Route("/admin/comments/{id}/edit", name="admin_comments_edit")
     *
     * @param Commentaires $comment
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function edit(Commentaires $comment, Request $request, EntityManagerInterface $manager)
    {
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $manager->persist($comment);
            $manager->flush();

            $this->addFlash(
                'success',
                "Le commentaire n°<strong>{$comment->getId()}</strong> a été modifié"
            );
        }

        return $this->render("admin/comment/edit.html.twig",[
            'comment' => $comment,
            'myForm' => $form->createView()
        ]);
    }

    /**
     * Permet de supprimer un commentaire
     * @Route("/admin/comments/{id}/delete", name="admin_comments_delete")
     *
     * @param Commentaires $comment
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function delete(Commentaires $comment, EntityManagerInterface $manager)
    {
        $this->addFlash(
            'success',
            "Le commentaire n°{$comment->getId()} a bien été supprimé"
        );
        $manager->remove($comment);
        $manager->flush();

        return $this->redirectToRoute('admin_comments_index');
    }

}