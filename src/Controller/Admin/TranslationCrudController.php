<?php

namespace App\Controller\Admin;

use App\Entity\Translation;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Form\Type\FileUploadType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints\File;

class TranslationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Translation::class;
    }
    
    public function configureFields(string $pageName): iterable
    {
        return [
            yield IdField::new('id')->hideOnForm(),

            yield TextField::new('name'),
            yield ChoiceField::new('translationType')->setChoices([
                'Chanson' => 'Chanson',
            ]),
            yield ChoiceField::new('translationStyle')->setChoices([
                'Electro Swing' => 'Electro Swing',
                'Pop' => 'Pop',
                'Rock' => 'Rock'
            ]),
            yield TextField::new('author'),
            yield ChoiceField::new('language')->setChoices([
                'Français' => 'Français',
                'Anglais' => 'Anglais',
            ]),
            yield DateTimeField::new('updatedAt')->hideOnForm()->hideOnIndex(),
            yield AssociationField::new('user')->hideOnForm()->autocomplete(),
            
            
            yield ImageField::new('translationFileName', 'File')
                ->onlyOnIndex()
                // ->setFormType(FileUploadType::class)
                ->setBasePath('/uploads/translations/'),

            // Upload du fichier
            yield ImageField::new('translationFileName', 'File')
                ->onlyOnForms()
                ->setFormType(FileUploadType::class)
                ->setFileConstraints([new File([
                    'mimeTypes' => [
                        'application/pdf', // pdf
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document', // docx
                    ]
                ])])
                ->setFormTypeOptions([
                    'attr' => [
                        'accept' => 'application/pdf'
                    ]
                ])
                ->setUploadDir('public/uploads/translations/')
                ->setUploadedFileNamePattern('[name]-[day][month][year]_[timestamp].[extension]'),
        ];
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        // Récupère la date actuelle et l'ajoute dans le champ de saisie (caché) du formulaire
        $entityInstance->setCreatedAt(new DateTimeImmutable());
        parent::persistEntity($entityManager, $entityInstance);

        // Récupère les données l'utilisateur connecté et l'ajoute dans le champ de saisie (caché) du formulaire
        $user = $this->getUser(); // je récupère l'utilisateur dans l'entité User
        $entityInstance->setUser($user); // Puis j'enregistre les données de l'utilisateur connecté
        parent::persistEntity($entityManager, $entityInstance);
    }
}
