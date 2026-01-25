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
                'data_class' => null,

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
                    'Chanson' => 'Chanson',
                    'Livre' => 'Livre',
                    'Texte' => 'Texte',
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
                    'Chanson' => [
                        'Electro Swing' => 'Electro Swing',
                        'Rock' => 'Rock',
                        'Pop' => 'Pop'
                    ],
                    'Livre' => [
                        'Fantasy' => 'Fantasy',
                    ],
                    'Texte' => [
                        'Poème' => 'Poème',
                    ]
                ]
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
                    'Français' => 'Français',
                    'Anglais' => 'Anglais',
                    'Espagnol' => 'Espagnol',
                ],
            ])
            // ->add('createdAt', null, [
            //     'row_attr' => [
            //         'class' => 'w-50-percent d-i-flex form-input-parent',
            //     ],

            //     'label' => 'Date de publication',
            //     'label_attr' => [
            //         'class' => 'w-50-percent mb-10 mt-first-label sign-label'
            //     ],
            //     'attr' => [
            //         'class' => 'form-input',
            //     ],
            //     'widget' => 'single_text'
            // ])
            // ->add('user', EntityType::class, [
            //     'class' => User::class,
            //     'choice_label' => 'id',
            //     'row_attr' => [
            //         'class' => 'w-50-percent d-i-flex form-input-parent',
            //     ],
            //     'label_attr' => [
            //         'class' => 'w-50-percent mb-10 mt-first-label sign-label'
            //     ],
            //     'attr' => [
            //         'class' => 'form-input',
            //     ],
            // ])
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
