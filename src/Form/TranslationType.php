<?php

namespace App\Form;

use App\Entity\Translation;
use App\Entity\Type;
use App\Entity\Style;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
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
            ->add('translationType', EnumType::class, [
                'class' => Type::class,
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
            ])
            ->add('translationStyle', EnumType::class, [
                'class' => Style::class,
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
                /* Je regroupe les différents styles contenant une chaîne de caractère spécifique : tous les styles contenant
                la chaîne "Chanson", seront regroupés dans une balise <optgroup>. Celle-ci contient des balises <option> pour
                chaque style (voir via l'inspection pour mieux comprendre).
                */
                'group_by' => function (Style $style, int $key, string $value): ?string {
                    if (str_contains($value, 'Chanson')) {
                        return 'Chanson';
                    }

                    if (str_contains($value, 'Livre')) {
                        return 'Livre';
                    }

                    if (str_contains($value, 'Texte')) {
                        return 'Texte';
                    }

                    return 'Autre';
                }
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
