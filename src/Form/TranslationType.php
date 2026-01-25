<?php

namespace App\Form;

use App\Entity\Translation;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TranslationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                // Ajout d'une classe à la balise div générée par $builder
                'row_attr' => [
                    'class' => 'w-50-percent d-i-flex form-input-parent',
                ],

                // Définition d'un nouveau texte pour le label
                'label' => 'Nom de la publication',

                // Attributs du label
                'label_attr' => [
                    'class' => 'w-50-percent mb-10 mt-first-label sign-label'
                ],
                
                // Attributs de l'input
                'attr' => [
                    'class' => 'form-input',
                ]
            ])
            ->add('translationFileName', FileType::class, [
                'row_attr' => [
                    'class' => 'w-50-percent d-i-flex form-input-parent',
                ],

                'label' => 'Votre fichier',
                'label_attr' => [
                    'class' => 'w-50-percent mb-10 mt-first-label sign-label'
                ],
                'attr' => [
                    'class' => 'form-input',
                ]
            ])
            ->add('translationType', ChoiceType::class, [
                // Ajout d'une classe à la balise div générée par $builder
                'row_attr' => [
                    'class' => 'w-50-percent d-i-flex form-input-parent',
                ],

                'label' => 'Type de contenu',
                'label_attr' => [
                    'class' => 'w-50-percent mb-10 mt-first-label sign-label'
                ],
                'attr' => [
                    'class' => 'form-input',
                ],
                'choices' => [
                    'Chanson' => 1,
                    'Livre' => 2,
                    'Texte' => 3,
                ],
            ])
            ->add('translationStyle', ChoiceType::class, [
                'row_attr' => [
                    'class' => 'w-50-percent d-i-flex form-input-parent',
                ],

                'label' => 'Style de la traduction',
                'label_attr' => [
                    'class' => 'w-50-percent mb-10 mt-first-label sign-label'
                ],
                'attr' => [
                    'class' => 'form-input',
                ],
                'choices' => [
                    'Electro Swing' => 1,
                    'Fantasy' => 2,
                    'Poème' => 3,
                ],
            ])
            ->add('author', TextType::class, [
                'row_attr' => [
                    'class' => 'w-50-percent d-i-flex form-input-parent',
                ],

                'label' => 'Auteur(rice)',
                'label_attr' => [
                    'class' => 'w-50-percent mb-10 mt-first-label sign-label'
                ],
                'attr' => [
                    'class' => 'form-input',
                ]
            ])
            ->add('language', ChoiceType::class, [
                'row_attr' => [
                    'class' => 'w-50-percent d-i-flex form-input-parent',
                ],

                'label' => 'Langue',
                'label_attr' => [
                    'class' => 'w-50-percent mb-10 mt-first-label sign-label'
                ],
                'attr' => [
                    'class' => 'form-input',
                ],
                'choices' => [
                    'Français' => 1,
                    'Anglais' => 2,
                    'Espagnol' => 3,
                ],
            ])
            ->add('save', SubmitType::class, [
                'row_attr' => [
                    'class' => 'w-50-percent d-i-flex form-input-parent',
                ],
                'label' => 'Publier',
                'attr' => [
                    'class' => 'submit-btn',
                    'name' => 'publier',
                    'id' => 'publier',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Translation::class,
        ]);
    }
}
