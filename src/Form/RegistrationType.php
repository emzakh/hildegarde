<?php

namespace App\Form;

use App\Entity\User;
use App\Form\ApplicationType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class RegistrationType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, $this->getConfiguration("Prénom","Votre prénom..."))
            ->add('lastName', TextType::class, $this->getConfiguration("Nom","Votre nom de famille..."))
            ->add('email', EmailType::class, $this->getConfiguration("Email","Votre adresse email..."))
            ->add('picture', FileType::class, [
                'label' => "Avatar (jpg,png,gif)",
                'required' => false
            ])
            ->add('password', PasswordType::class, $this->getConfiguration("Mot de passe","Votre mot de passe"))
            ->add('passwordConfirm',PasswordType::class, $this->getConfiguration("Confirmation de mot de passe","Veuillez confirmer votre mot de passe"))
            ->add('presentation', TextType::class, $this->getConfiguration("Présentation","Présentez-vous !"))

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'validation_groups' => ['Default', 'Registration']
        ]);
    }
}