<?php

namespace App\Controller;

use App\Entity\Produits;
use App\Entity\PropertySearch;
use App\Form\PropertySearchType;
use App\Repository\RecettesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index(Request $request, RecettesRepository $recettesRepo): Response
    {
        $propertySearch = new PropertySearch();
        $form = $this->createForm(PropertySearchType::class, $propertySearch);
        $form->handleRequest($request);
        //initialement le tableau des produits est vide,
        //c.a.d on affiche les articles que lorsque l'utilisateur clique sur le bouton rechercher
        $produits = [];

        if ($form->isSubmitted() && $form->isValid()) {
            //on récupère le nom de produit tapé dans le formulaire
            $nom = $propertySearch->getNom();

            if ($nom != "" )
                //si on a fourni un nom de produit on affiche tous les produits qui match
                $produits = $this->getDoctrine()->getRepository(Produits::class)->findBy([
                    'nom' => $nom,

                ]);
            else
                //si si aucun nom n'est fourni on affiche tous les produits
                $produits = $this->getDoctrine()->getRepository(Produits::class)->findAll();
        }
            return $this->render('home.html.twig', [
                'recettes' => $recettesRepo->findBestRecettes(3),
                'form' => $form->createView(), 'produits' => $produits
            ]);
        }
    }