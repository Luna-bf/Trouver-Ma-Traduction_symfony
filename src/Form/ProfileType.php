<?php

namespace App\Form;

use App\Entity\Profile;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                // Label personnalisé
                'label' => 'Nom d\'utilisateur',

                // Attributs de la div générée par $builder
                'row_attr' => [
                    'class' => 'd-flex flex-column form-parent-row'
                ],

                // Attributs du label
                'label_attr' => [
                    'class' => 'mt-label'
                ],
            ])
            ->add('profilePictureName', FileType::class, [
                // Label personnalisé
                'label' => 'Photo de profil (optionnel)',

                // Attributs de la div générée par $builder
                'row_attr' => [
                    'class' => 'd-flex flex-column form-parent-row'
                ],

                // Attributs du label
                'label_attr' => [
                    'class' => 'mt-label'
                ],
            ])
            ->add('thumbnailName', FileType::class, [
                // Label personnalisé
                'label' => 'Bannière de profil (optionnel)',

                // Attributs de la div générée par $builder
                'row_attr' => [
                    'class' => 'd-flex flex-column form-parent-row'
                ],

                // Attributs du label
                'label_attr' => [
                    'class' => 'mt-label'
                ],
            ])
            ->add('description', TextareaType::class, [
                // Label personnalisé
                'label' => 'Description (optionnel)',

                // Attributs de la div générée par $builder
                'row_attr' => [
                    'class' => 'd-flex flex-column form-parent-row'
                ],

                // Attributs du label
                'label_attr' => [
                    'class' => 'mt-label'
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Enregistrer',

                'row_attr' => [
                    'class' => 'd-flex'
                ],

                'attr' => [
                    'class' => 'submit-btn text-white w-50 rounded'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Profile::class,
        ]);
    }
}
