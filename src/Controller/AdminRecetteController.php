<?php

namespace App\Controller;

use App\Repository\RecettesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminRecetteController extends AbstractController
{
    /**
     * @Route("/admin/ads", name="admin_ads_index")
     */
    public function index(RecettesRepository  $repo): Response
    {
        return $this->render('admin/recette/index.html.twig', [
            'ads' => $repo->findAll()
        ]);
    }
}