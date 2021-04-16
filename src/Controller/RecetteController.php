<?php

namespace App\Controller;


use App\Repository\RecettesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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


}
