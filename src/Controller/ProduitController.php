<?php

namespace App\Controller;

use App\Form\ProduitEditType;
use App\Form\ProduitType;
use App\Entity\Produits;
use App\Repository\ProduitsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProduitController extends AbstractController
{
    /**
     * @Route("/produits", name="produits_index")
     * @param ProduitsRepository $repo
     * @return Response
     */
    public function index(ProduitsRepository $repo): Response
    {
        //$repo = $this->getDoctrine()->getRepository(Produit::class);

        $produits = $repo->findAll();

       // dump($produits);

        return $this->render('produit/index.html.twig', [
            'produits' => $produits
        ]);
    }

    /**
     * Permet de créer un produit
     * @Route("/produit/new", name="produit_create")
     *
     * @param EntityManagerInterface $manager
     * @param Request $request
     * @return Response
     */
    public function create(EntityManagerInterface $manager, Request $request)
    {
        $produit = new Produits();
        $form = $this ->createForm(ProduitType::class, $produit);

        $form->handleRequest($request);

        //dump($ad);

        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($produit);
            $manager->flush();

            $this->addFlash(
                'success',
                "Le produit <strong>{$produit->getNom()}</strong> a bien été enregistrée"
            );

            return $this->redirectToRoute('produit_show',[
                'slug' => $produit->getSlug()
            ]);

        }

        return $this->render('produit/new.html.twig',[
            'myForm' => $form->createView()
        ]);
    }

    /**
     * Permet de modifier un produit
     * @Route("/produit/{slug}/edit", name="produit_edit")
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param Produits $produit
     * @return Response
     */
    public function edit(Request $request, EntityManagerInterface $manager, Produits $produit)
    {
        $form = $this->createForm(ProduitEditType::class, $produit);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $produit->setSlug(''); // pour que initialize slug

     //       foreach($produit->getImages() as $image){
       //         $image->setAd($produit);
         //       $manager->persist($image);
           // }

            $manager->persist($produit);
            $manager->flush();

            $this->addFlash(
                'success',
                "Le produit <strong>{$produit->getNom()}</strong> a bien été modifiée"
            );

            return $this->redirectToRoute('produit_show',[
                'slug' => $produit->getSlug()
            ]);
        }


        return $this->render("produit/edit.html.twig",[
            "produit" => $produit,
            "myForm" => $form->createView()
        ]);

    }

    /**
     * Permet d'afficher un seul produit
     * @Route("/produit/{slug}", name="produit_show")
     *
     * @param Produits $produit
     * @return Response
     */
    public function show(Produits $produit)
    {
        //$repo = $this->getDoctrine()->getRepository(Ad::class);
        //$ad = $repo->findOneBySlug($slug);


        //dump($ad);

        return $this->render('produit/show.html.twig',[
            'produit' => $produit
        ]);



    }






}

