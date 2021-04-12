<?php

namespace App\DataFixtures;

use App\Entity\Produits;
use Faker\Factory;
use Cocur\Slugify\Slugify;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('FR-fr');
        $slugify = new Slugify();

        for($a = 1; $a <= 30; $a++){
            $produit = new Produits();
            $nom = $faker->word;
            $nomlatin = $faker->word;
            $categorie = $faker->word;
            $slug = $slugify->slugify($nom);
           // $image = $faker->imageUrl(500,250);
            $effets = $faker->sentence();
            $description = $faker->paragraph(2);


            $produit->setNom($nom)
                ->setNomlatin($nomlatin)
                ->setCategorie($categorie)
                ->setSlug($slug)
                ->setImage('https://picsum.photos/500/250')
                ->setEffets($effets)
                ->setDescription($description);



            $manager->persist($produit);



        }
        $manager->flush();
    }
}