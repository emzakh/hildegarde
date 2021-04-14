<?php

namespace App\Form;

use App\Entity\Produits;
use Faker\Provider\Text;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ProduitType extends AbstractType
{

    private function getConfiguration($label,$placeholder, $options=[]){
        return array_merge([
            'label'=>$label,
            'attr'=> [
                'placeholder'=>$placeholder
            ]

        ], $options);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => "Nom",
                'attr' => [
                    'placeholder'=>'Nom du produit'
                ]
            ])
            ->add('nomlatin', TextType::class, $this->getConfiguration('Nom Latin', 'Nom latin du produit'))
            ->add('categorie',ChoiceType::class,[
                'choices' => [
                    'Jardin'=>'Jardin',
                    'Potager'=>'Potager',
                    'Epices'=>'Epices'
                ]
            ])
            ->add('effets', TextType::class, $this->getConfiguration('Introduction','Donnez une description globale de l\'annonce'))
            ->add('description', TextareaType::class, $this->getConfiguration('Description','Description du produit'))
            ->add('image', UrlType::class, $this->getConfiguration('URL de l\'image','Donnez l\'adresse de votre image'))
            ->add('slug', TextType::class, $this->getConfiguration('Slug','Adresse web (automatique)',[
                'required' => false
            ]))


        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Produits::class,
        ]);
    }
}
