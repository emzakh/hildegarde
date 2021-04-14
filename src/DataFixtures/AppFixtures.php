<?php

namespace App\DataFixtures;

use App\Entity\Commentaires;
use App\Entity\Produits;
use App\Entity\Recettes;
use App\Entity\User;
use Faker\Factory;
use Cocur\Slugify\Slugify;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    // gestion du hash de password
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }


    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('FR-fr');
        //$slugify = new Slugify();

        $admin = new User();
        $admin->setFirstName('Maximino')
            ->setLastName('Gutierrez Mantione')
            ->setEmail('max@epse.be')
            ->setPassword($this->encoder->encodePassword($admin,'epse'))
            ->setRoles(['ROLE_ADMIN']);
        $manager->persist($admin);



        // gestion des utilisateurs
        $users = [];

        for($u=1; $u <= 10; $u++){
            $user = new User();
            $hash = $this->encoder->encodePassword($user,'password');

            $user->setFirstName($faker->firstName())
                ->setLastName($faker->lastName)
                ->setEmail($faker->email)
                ->setPassword($hash)
                ;

            $manager->persist($user);
            $users[]= $user; // ajouter l'utilisateur fraichement créé dans le tableau pour l'association avec les recettes

        }

        $produits = [];
        for ($a = 1; $a <= 30; $a++) {
            $produit = new Produits();
            $nom = $faker->word;
            $nomlatin = $faker->word;
            $categorie = $faker->word;
           // $slug = $slugify->slugify($nom);
            // $image = $faker->imageUrl(500,250);
            $effets = $faker->sentence();
            $description = $faker->paragraph(2);


            $produit->setNom($nom)
                ->setNomlatin($nomlatin)
                ->setCategorie($categorie)
              //  ->setSlug($slug)
                ->setImage('https://picsum.photos/500/250')
                ->setEffets($effets)
                ->setDescription($description);


            $manager->persist($produit);
            $produits[] = $produit;

        }


        for ($b = 1; $b <= rand(0, 10); $b++) {
            $recette = new Recettes();

            $titre = $faker->word;
            $date = $faker->dateTimeBetween('-6 months', '-4 months');
            $description = $faker->paragraph(2);
            $etapes = $faker->paragraph(5);
           $ingredients = $produits[rand(0, count($produits))];
          //  $commentaires = $faker->paragraph;
          //  $types = $faker->word;
            $author = $users[rand(0, count($users) - 1)];


            $recette->setTitre($titre)
                ->setDate($date)
                ->setDescription($description)
                ->setEtapes($etapes)
              ->addIngredient($ingredients)
             // ->addCommentaire($commentaires)
             //   ->setTypes($types)
                ->setAuthor($author);

            $manager->persist($recette);


            // gestion des commentaires
            if (rand(0, 1)) {
                $commentaires = new Commentaires();
                $commentaires->setContenu($faker->paragraph())
                    ->setRating(rand(1, 5))
                    ->setAuthor($author)
                    ->setRecette($recette);
                $manager->persist($commentaires);
            }

            $manager->flush();
        }
    }
}