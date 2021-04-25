<?php

namespace App\Controller;


use App\Entity\Recettes;
use App\Form\RecetteEditType;
use App\Form\RecetteType;
use App\Repository\RecettesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
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
     * Permet de créer une recette
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

            );

        }

        return $this->render('recette/new.html.twig',[
            'myForm' => $form->createView()
        ]);
    }

    /**
     * Permet de modifier une recette
     * @Route("/recette/{slug}/edit", name="recette_edit")
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param Recettes $recette
     * @return Response
     */
    public function edit(Request $request, EntityManagerInterface $manager, Recettes $recette)
    {
        $form = $this->createForm(RecetteEditType::class, $recette);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $recette->setSlug(''); // pour que initialize slug


            $manager->persist($recette);
            $manager->flush();

            $this->addFlash(
                'success',
                "La recette <strong>{$recette->getTitre()}</strong> a bien été modifiée"
            );

            return $this->redirectToRoute('recette_show',[
                'slug' => $recette->getSlug()
            ]);
        }


        return $this->render("recette/edit.html.twig",[
            "recette" => $recette,
            "myForm" => $form->createView()
        ]);

    }

    /**
     * Permet d'afficher une seule recette
     * @Route("/recette/{slug}", name="recette_show")
     * @param Recettes $recette
     * @return Response
     */
    public function show(Recettes $recette)

    {
        return $this->render('recette/show.html.twig',[
            'recette' => $recette
        ]);

    }


}
