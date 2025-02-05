<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\SubmitButton;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CoverType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Votre nom',
                'attr' => [
                    'placeholder' => 'Votre Nom',
                    'class' => 'form-control w-full p-2 border rounded'
                ],
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Votre prénom',
                'attr' => [
                    'placeholder' => 'Votre Prénom',
                    'class' => 'form-control w-full p-2 border rounded'
                ],
            ])
            ->add('diplome', TextareaType::class, [
                'label' => 'Votre diplôme',
                'attr' => [
                    'placeholder' => 'Votre Diplôme',
                    'class' => 'form-control w-full p-2 border rounded'
                ],
            ])
            ->add('entreprise', TextType::class, [
                'label' => 'Votre entreprise',
                'attr' => [
                    'placeholder' => 'Votre Entreprise',
                    'class' => 'form-control w-full p-2 border rounded'
                ],
            ])
            ->add('poste', TextType::class, [
                'label' => 'Votre poste',
                'attr' => [
                    'placeholder' => 'Votre Poste',
                    'class' => 'form-control w-full p-2 border rounded'
                ],
            ])
            ->add('annonce', TextareaType::class, [
                'label' => 'Offre d\'emploi',
                'attr' => [
                    'placeholder' => 'Annonce',
                    'class' => 'form-control w-full p-2 border rounded'
                ],
            ])
            // ->add('submit', SubmitType::class, [
            //     'label' => 'Générer une lettre de motivation',
            //     'attr' => [
            //         'class' => 'bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-full',
            //     ],
            // ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
