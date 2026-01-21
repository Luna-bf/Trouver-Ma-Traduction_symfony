<?php

namespace App\Form;

use App\Entity\Profile;
use App\Entity\Translation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TranslationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('translation_file_name', TextType::class, [
                'attr' => [
                    'class' => 'submit-btn',
                ]
            ])
            ->add('translation_type')
            ->add('translation_style')
            ->add('author')
            ->add('language')
            ->add('createdAt', null, [
                'widget' => 'single_text'
            ])
            ->add('profile', EntityType::class, [
                'class' => Profile::class,
                'choice_label' => 'id',
            ])
            ->add('Publier', SubmitType::class, [
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
