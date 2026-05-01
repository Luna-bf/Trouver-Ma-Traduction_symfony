<?php

namespace App\Form;

use App\Entity\Translation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class TranslationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                // Ajout d'une classe à la balise div générée par $builder
                'row_attr' => [
                    'class' => 'd-flex flex-column form-parent-row',
                ],

                // Définition d'un nouveau texte pour le label
                'label' => 'Nom de la publication',

                // Attributs du label
                'label_attr' => [
                    'class' => 'mt-label'
                ]
            ])
            ->add('translationFile', FileType::class, [

                'mapped' => false,
                
                'required' => $options['is_file_required'],

                'row_attr' => [
                    'class' => 'd-flex flex-column form-parent-row',
                ],

                'label' => 'Votre fichier',
                'label_attr' => [
                    'class' => 'mt-label'
                ],
                'attr' => [
                    'class' => 'form-input text-black',
                ],

                'constraints' => [
                    new Assert\File(
                        maxSize: '1024k',
                        extensions: ['pdf', 'docx'],
                        extensionsMessage: 'Veuillez joindre un document valide.',
                    )
                ],
            ])
            ->add('translationType', ChoiceType::class, [
                // Ajout d'une classe à la balise div générée par $builder
                'row_attr' => [
                    'class' => 'd-flex flex-column form-parent-row',
                ],

                'label' => 'Type de contenu',
                'label_attr' => [
                    'class' => 'mt-label'
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
                    'class' => 'd-flex flex-column form-parent-row',
                ],

                'label' => 'Style de la traduction',
                'label_attr' => [
                    'class' => 'mt-label'
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
                    'class' => 'd-flex flex-column form-parent-row',
                ],

                'label' => 'Auteur(rice)',
                'label_attr' => [
                    'class' => 'mt-label'
                ],
                'attr' => [
                    'class' => 'form-input',
                ]
            ])
            ->add('language', ChoiceType::class, [
                'row_attr' => [
                    'class' => 'd-flex flex-column form-parent-row',
                ],

                'label' => 'Langue',
                'label_attr' => [
                    'class' => 'mt-label'
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
            ->add('save', SubmitType::class, [
                'row_attr' => [
                    'class' => 'd-flex'
                ],
                'label' => 'Publier',
                'attr' => [
                    'class' => 'submit-btn text-white w-50 rounded',
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
        $resolver->setRequired('is_file_required');
    }
}
