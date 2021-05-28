<?php

namespace App\Controller;

use App\Entity\Produits;
use App\Form\ProduitEditType;
use App\Form\ProduitType;
use App\Repository\ProduitsRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class AdminProduitController extends AbstractController
{
    /**
     * Permet d'afficher l'ensemble des produits
     * @Route("/admin/produits", name="admin_produits_index")
     * @param ProduitsRepository $repo
     * @return Response
     */
    public function index(ProduitsRepository  $repo): Response
    {
        return $this->render('admin/produit/index.html.twig', [
            'produits' => $repo->findAll()
        ]);
    }


    /**
     * Permet de modifier un produit
     * @Route("/produit/{id}/edit", name="admin_produits_edit")
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

            return $this->redirectToRoute('admin_produits_index',[
                'id' => $produit->getId()
            ]);
        }


        return $this->render("admin/produit/edit.html.twig",[
            "produit" => $produit,
            "myForm" => $form->createView()
        ]);

    }

    /**
     * Permet de supprimer une recette
     * @Route("admin/produit/{id}/delete", name="admin_produits_delete")
     * @Security("is_granted('ROLE_ADMIN')")
     * @param Produits $produit
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function delete(Produits $produit, EntityManagerInterface $manager)
    {
        $this->addFlash(
            'success',
            "Le produit <strong>{$produit->getNom()}</strong> a bien été supprimée"
        );
        $manager->remove($produit);
        $manager->flush();
        return $this->redirectToRoute("admin_produits_index");
    }

}