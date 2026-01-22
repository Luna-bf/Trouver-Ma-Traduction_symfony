<?php

namespace App\Form;

use App\Entity\Profile;
use App\Entity\Translation;
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
            ->add('translation_file_name', FileType::class, [
                'label' => 'Votre fichier',
                'label_attr' => [
                    'class' => 'w-50-percent mb-10 mt-first-label sign-label'
                ],
                'attr' => [
                    'class' => 'form-input',
                ]
            ])
            ->add('translation_type', ChoiceType::class, [
                'label' => 'Type de contenu',
                'label_attr' => [
                    'class' => 'w-50-percent mb-10 mt-first-label sign-label'
                ],
                'attr' => [
                    'class' => 'form-input',
                ],
                'choices' => [
                    'Apple' => 1,
                    'Banana' => 2,
                    'Durian' => 3,
                ],
                'choice_attr' => [
                    'Apple' => ['data-color' => 'Red'],
                    'Banana' => ['data-color' => 'Yellow'],
                    'Durian' => ['data-color' => 'Green'],
                ],
            ])
            ->add('translation_style', ChoiceType::class, [
                'label' => 'Style de la traduction',
                'label_attr' => [
                    'class' => 'w-50-percent mb-10 mt-first-label sign-label'
                ],
                'attr' => [
                    'class' => 'form-input',
                ],
                'choices' => [
                    'Apple' => 1,
                    'Banana' => 2,
                    'Durian' => 3,
                ],
                'choice_attr' => [
                    'Apple' => ['data-color' => 'Red'],
                    'Banana' => ['data-color' => 'Yellow'],
                    'Durian' => ['data-color' => 'Green'],
                ]
            ])
            ->add('author', TextType::class, [
                'label' => 'Auteur(rice)',
                'label_attr' => [
                    'class' => 'w-50-percent mb-10 mt-first-label sign-label'
                ],
                'attr' => [
                    'class' => 'form-input',
                ]
            ])
            ->add('language', ChoiceType::class, [
                'label' => 'Langue',
                'label_attr' => [
                    'class' => 'w-50-percent mb-10 mt-first-label sign-label'
                ],
                'attr' => [
                    'class' => 'form-input',
                ],
                'choices' => [
                    'Apple' => 1,
                    'Banana' => 2,
                    'Durian' => 3,
                ],
                'choice_attr' => [
                    'Apple' => ['data-color' => 'Red'],
                    'Banana' => ['data-color' => 'Yellow'],
                    'Durian' => ['data-color' => 'Green'],
                ]
            ])
            ->add('createdAt', null, [
                'label' => 'Date de publication',
                'label_attr' => [
                    'class' => 'w-50-percent mb-10 mt-first-label sign-label'
                ],
                'attr' => [
                    'class' => 'form-input',
                ],
                'widget' => 'single_text'
            ])
            ->add('profile', EntityType::class, [
                'class' => Profile::class,
                'choice_label' => 'id',
            ])
            ->add('save', SubmitType::class, [
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
