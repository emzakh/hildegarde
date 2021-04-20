<?php

namespace App\Form;

use App\Entity\Produits;
use App\Entity\Recettes;
use App\Entity\Type;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecetteType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Titre', TextType::class, [
                'label' => "Titre",
                'attr' => [
                    'placeholder'=>'Titre de la recette'
                ]
            ])
            ->add('description', TextareaType::class, $this->getConfiguration('Description','Description de la recette'))
            ->add('etapes', TextareaType::class, $this->getConfiguration('Etapes','1. Première étape de votre recette.'))

            ->add('ingredients',  EntityType::class, array(
                'class' => Produits::class,
                'choice_label' => 'nom',
                'expanded'  => true,
                'multiple' => true

            ))


            ->add('types', EntityType::class, [
                'class' => Type::class,
                'choice_label' => 'nom',
                'expanded' => false,
                'multiple' => true
            ]);

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Recettes::class,
        ]);
    }
}
