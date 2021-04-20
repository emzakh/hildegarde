<?php

namespace App\Controller;


use App\Entity\Recettes;
use App\Form\RecetteType;
use App\Repository\RecettesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RecetteController extends AbstractController
{
    /**
     * @Route("/recettes", name="recettes_index")
     * @param RecettesRepository $repo
     * @return Response
     */
    public function index(RecettesRepository $repo): Response
    {

        $recettes = $repo->findAll();


        return $this->render('recette/index.html.twig', [
            'recettes' =>$recettes

        ]);
    }

    /**
     * Permet de créer un produit
     * @Route("/recette/new", name="recette_create")
     *
     * @param EntityManagerInterface $manager
     * @param Request $request
     * @return Response
     */
    public function create(EntityManagerInterface $manager, Request $request)
    {
        $recette = new Recettes();
        $form = $this ->createForm(RecetteType::class, $recette);

        $form->handleRequest($request);



        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($recette);
            $manager->flush();

            $this->addFlash(
                'success',
                "La recette <strong>{$recette->getTitre()}</strong> a bien été enregistrée"
            );

            return $this->redirectToRoute('recettes_index'//,[
             //   'slug' => $produit->getSlug()
            //]//
            );

        }

        return $this->render('recette/new.html.twig',[
            'myForm' => $form->createView()
        ]);
    }


}
