<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                // Attributs de la div générée par $builder
                'row_attr' => [
                    'class' => 'w-50-percent d-i-flex form-input-parent'
                ],

                // Attributs du label
                'label_attr' => [
                    'class' => 'w-50-percent mb-10 mt-first-label sign-label'
                ],

                // Attributs de l'input
                'attr' => [
                    'class' => 'form-input',
                ]
            ])
            ->add('email', EmailType::class, [
                // Attributs de la div générée par $builder
                'row_attr' => [
                    'class' => 'w-50-percent d-i-flex form-input-parent'
                ],

                // Attributs du label
                'label_attr' => [
                    'class' => 'w-50-percent mb-10 mt-first-label sign-label'
                ],

                // Attributs de l'input
                'attr' => [
                    'class' => 'form-input',
                ]
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
                
                // Attributs de la div générée par $builder
                'row_attr' => [
                    'id' => 'remember'
                ],

                // Attributs du label
                'label_attr' => [
                    'class' => 'sign-label'
                ],

                // Attributs de l'input
                'attr' => [
                    'id' => 'remember-input',
                ]
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => [
                    'autocomplete' => 'new-password',
                    'class' => 'form-input'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez définir un mot de passe.',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Le mot de passe doit contenir au moins {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],

                'row_attr' => [
                    'class' => 'w-50-percent d-i-flex form-input-parent'
                ],

                // Attributs du label
                'label_attr' => [
                    'class' => 'w-50-percent mb-10 mt-first-label sign-label'
                ],
            ])
            ->add('Inscription', SubmitType::class, [
                'row_attr' => [
                    'class' => 'd-i-flex'
                ],
                'attr' => [
                    'class' => 'submit-btn'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
