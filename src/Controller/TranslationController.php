<?php

namespace App\Controller;

use App\Entity\Translation;
use App\Form\TranslationType;
use App\Repository\TranslationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('', name: 'translation_')]
final class TranslationController extends AbstractController
{
    #[Route('', name: 'landing')]
    public function landing(): Response
    {
        return $this->render('translation/index.html.twig', [
            'controller_name' => 'TranslationController',
        ]);
    }

    #[Route('form/login', name: 'login')]
    public function login(): Response
    {
        return $this->render('translation/form/login.html.twig', [
            'controller_name' => 'TranslationController',
        ]);
    }

    #[Route('home', name: 'home')]
    public function home(TranslationRepository $repo): Response
    {
        return $this->render('translation/home/home.html.twig', [
            'translations' => $repo->findAll(),
        ]);
    }

    #[Route('home/search_result', name: 'search_result')]
    public function searchResult(): Response
    {
        return $this->render('translation/home/searchResult.html.twig', [
            'controller_name' => 'TranslationController',
        ]);
    }

    #[Route('file/file_viewer', name: 'file_viewer')]
    public function fileViewer(): Response
    {
        return $this->render('translation/file/fileViewer.html.twig', [
            'controller_name' => 'TranslationController',
        ]);
    }

    // Pour l'envoi de fichiers PDF : https://symfony.com/doc/current/controller/upload_file.html
    #[Route('/posts/new_upload', name: 'new_upload')]
    public function newUpload(Request $request, EntityManagerInterface $em, SluggerInterface $slugger, #[Autowire('%kernel.project_dir%/public/uploads/translations')] string $translationsDirectory): Response
    {
        // Création d'une nouvelle instance de Translation
        $translation = new Translation();

        // Initialisation du formulaire
        $translationForm = $this->createForm(TranslationType::class, $translation);

        // Traitement du formulaire
        $translationForm->handleRequest($request);

        // Vérifie si le formulaire est valide
        if ($translationForm->isSubmitted() && $translationForm->isValid()) {

            $translationFile = $translationForm->get('translationFile')->getData();

            $fullTranslation = $translationForm->getData(); // Récupère les données du formulaire
            $fullTranslation->setCreatedAt(new \DateTimeImmutable()); // Enregistre la date dans le champ du formulaire (setter)
            $user = $this->getUser(); // Récupère les données l'utilisateur connecté
            $fullTranslation->setUser($user); // Enregistre les données de l'utilisateur connecté dans le champ du formulaire (setter)

            // Traitement du fichier
            if ($translationFile) {
                $originalFilename = pathinfo($translationFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $translationFile->guessExtension();

                // Envoi du fichier dans le dossier où les traductions sont stockées
                try {
                    $translationFile->move($translationsDirectory, $newFilename);
                } catch (FileException $e) {
                    dd('Echec');
                }

                // updates the 'translationFilename' property to store the PDF file name instead of its contents
                $fullTranslation->setTranslationFileName($newFilename);
            }

            $em->persist($fullTranslation); // Prépare la requête
            $em->flush(); // Exécute la requête

            $this->addFlash('success', 'Traduction publiée avec succès.');

            return $this->redirectToRoute('user_index');
        }

        return $this->render('translation/posts/newUpload.html.twig', [
            'translationForm' => $translationForm->createView(),
        ]);
    }

    #[Route('translation/posts/edit/{id}', name: 'edit')]
    public function edit(Translation $translation, Request $request, EntityManagerInterface $em, SluggerInterface $slugger, #[Autowire('%kernel.project_dir%/public/uploads/translations')] string $translationsDirectory): Response
    {
        // Je n'ai pas besoin de déclarer une nouvelle instance de la classe Translation car je veux modifier des données déjà existantes
        // Initialisation du formulaire
        $editTranslationForm = $this->createForm(TranslationType::class, $translation);

        // if (!empty($translation->getTranslationFileName())) {
        //     $translation->setTranslationFile(
        //         new File($translationsDirectory . '/' . $translation->getTranslationFileName())
        //     );
        // }

        // Traitement du formulaire
        $editTranslationForm->handleRequest($request);

        // Vérifie si le formulaire est valide
        if ($editTranslationForm->isSubmitted() && $editTranslationForm->isValid()) {

            $translationFile = $editTranslationForm->get('translationFile')->getData(); // Récupère le fichier dans le champs du formulaire
            $translationsDirectory = $this->getParameter('kernel.project_dir') . '/public/uploads/translations'; // Le dossier où sera stocké le fichier

            // $translation->setTranslationFileName($newFilename);

            /* 
            Sources utile :
                https://stackoverflow.com/questions/45060712/symfony-3-file-upload-and-db-if-new-file-not-uploaded-old-file-field-removed
                https://stackoverflow.com/questions/19563295/symfony2-file-upload-delete-old-and-create-new-in-edit
            */
            /*
            Source à checker en priorité !
                https://stackoverflow.com/questions/43356878/delete-file-when-entity-is-deleted-in-symfony

                Entity listeners, they are defined as classes with callback methods for the events you want to respond to.
                They can use services, but they are only called for the entities of a certain class, so they are ideal for
                complex event logic related to a single entity;

                https://www.doctrine-project.org/projects/doctrine-phpcr-odm/en/latest/reference/events.html#lifecycle-events
            */
            if (!empty($translationFile)) {
                
                $originalFilename = pathinfo($translationFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $translationFile->guessExtension();

                $translationFile->move($translationsDirectory, $newFilename);
                $translation->setTranslationFileName($newFilename);
            }

            $em->persist($translation); // Prépare la requête
            $em->flush(); // Exécute la requête

            $this->addFlash('success', 'Traduction modifiée avec succès.');

            return $this->redirectToRoute('user_index');
        }

        return $this->render('translation/posts/edit.html.twig', [
            'editTranslationForm' => $editTranslationForm->createView()
        ]);
    }

    #[Route('translation/posts/{id}/delete', name: 'delete', methods: ['GET'])]
    public function delete($id, TranslationRepository $repo, EntityManagerInterface $em): Response
    {
        $translation = $repo->find($id);
        $em->remove($translation);

        $em->flush();

        $this->addFlash('success', 'Traduction supprimée avec succès.');

        return $this->redirectToRoute('user_index');
    }
}
