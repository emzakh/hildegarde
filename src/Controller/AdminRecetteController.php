<?php

namespace App\Controller;

use App\Entity\Recettes;
use App\Form\RecetteEditType;
use App\Form\RecetteType;
use App\Repository\RecettesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class AdminRecetteController extends AbstractController
{
    /**
     * Permet d'afficher l'ensemble des recettes
     * @Route("/admin/recettes", name="admin_recettes_index")
     * @param RecettesRepository $repo
     * @return Response
     */
    public function index(RecettesRepository $repo): Response
    {
        return $this->render('admin/recette/index.html.twig', [
            'recettes' => $repo->findAll()
        ]);
    }


    /**
     * Permet d'afficher le formulaire d'édition
     * @Route("/admin/recettes/{id}/edit", name="admin_recettes_edit")
     * @param Recettes $recette
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function edit(Recettes $recette, Request $request, EntityManagerInterface $manager)
    {
        $form = $this->createForm(RecetteType::class, $recette);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $file = $form['imgRecette']->getData();
            if(!empty($file)){
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();
                try{
                    $file->move(
                        $this->getParameter('uploads_directory'),
                        $newFilename
                    );
                }
                catch(FileException $e)
                {
                    return $e->getMessage();
                }

                $recette->setImgRecette($newFilename);
            }
            $manager->persist($recette);
            $manager->flush();

            $this->addFlash(
                "success",
                "La recette <strong>{$recette->getTitre()}</strong> a bien été modifiée"
            );
        }

        return $this->render("admin/recette/edit.html.twig",[
            'recette' => $recette,
            'myForm' => $form->createView()
        ]);
    }
}