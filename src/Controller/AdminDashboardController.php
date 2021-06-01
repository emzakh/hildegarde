<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminDashboardController extends AbstractController
{
    /**
     * @Route("/admin", name="admin_dashboard_index")
     */
    public function index(EntityManagerInterface $manager): Response
    {
        $users = $manager->createQuery('SELECT COUNT(u) FROM App\Entity\User u')
            ->getSingleScalarResult(); // pour récup une valeur sinon c'est un tableau
        $produits = $manager->createQuery("SELECT COUNT(p) FROM App\Entity\Produits p")
            ->getSingleScalarResult();
        $recettes = $manager->createQuery("SELECT COUNT(r) FROM App\Entity\Recettes r")
            ->getSingleScalarResult();
        $commentaires = $manager->createQuery("SELECT COUNT(c) FROM App\Entity\Commentaires c")
            ->getSingleScalarResult();


        $bestRecettes = $manager->createQuery(
            'SELECT AVG(c.rating) as note, r.titre, r.id, u.firstName, u.lastName
            FROM App\Entity\Commentaires c
            JOIN c.recette r
            JOIN r.author u
            GROUP BY r
            ORDER BY note DESC'
        )
            ->setMaxResults(5)
            ->getResult();

        $worstRecettes = $manager->createQuery(
            'SELECT AVG(c.rating) as note, r.titre, r.id, u.firstName, u.lastName
            FROM App\Entity\Commentaires c
            JOIN c.recette r
            JOIN r.author u
            GROUP BY r
            ORDER BY note ASC'
        )
            ->setMaxResults(5)
            ->getResult();



        return $this->render('admin/dashboard/index.html.twig', [
            'stats' => [
                'users' => $users,
                'produits' => $produits,
                'recettes' => $recettes,
                'commentaires' => $commentaires
            ],
            'bestRecettes' => $bestRecettes,
            'worstRecettes' => $worstRecettes
        ]);
    }
}