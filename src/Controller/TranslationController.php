<?php

namespace App\Controller;

use App\Entity\Translation;
use App\Entity\User;
use App\Form\TranslationType;
use App\Repository\TranslationRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('', name: 'translation_')]
final class TranslationController extends AbstractController
{
    #[Route('', name: 'index')]
    public function index(): Response
    {
        return $this->render('translation/index.html.twig', [
            'controller_name' => 'TranslationController',
        ]);
    }

    #[Route('home', name: 'home')]
    public function home(#[CurrentUser] User $user, TranslationRepository $repo): Response
    {
        $profile = $user->getProfile();

        return $this->render('translation/home/home.html.twig', [
            'translations' => $repo->findAll(),
            'profile' => $profile
        ]);
    }

    #[Route('home/search_result', name: 'search_result')]
    public function searchResult(#[CurrentUser] User $user): Response
    {
        $profile = $user->getProfile();

        return $this->render('translation/home/searchResult.html.twig', [
            'controller_name' => 'TranslationController',
            'profile' => $profile
        ]);
    }

    #[IsGranted("ROLE_USER")]
    #[Route('/posts/new_upload', name: 'new_upload')]
    public function newUpload(Request $request, #[CurrentUser] User $user, EntityManagerInterface $em, FileUploader $fileUploader): Response
    {
        // Création d'une nouvelle instance de Translation
        $translation = new Translation();
        $img = "";

        // Initialisation du formulaire
        $translationForm = $this->createForm(TranslationType::class, $translation, [
            'is_file_required' => true // Ici, le champ "translation_file_name" est requis
        ]);

        // Traitement du formulaire
        $translationForm->handleRequest($request);

        // Vérifie si le formulaire est valide
        if ($translationForm->isSubmitted() && $translationForm->isValid()) {

            $fullTranslation = $translationForm->getData(); // Récupère toutes les données du formulaire
            $translationFile = $translationForm->get('translationFile')->getData(); // Récupère le fichier du champ "translationFile"

            $fullTranslation->setCreatedAt(new \DateTimeImmutable()); // Enregistre la date dans le champ du formulaire (setter)

            $profile = $user->getProfile(); // Récupère les données l'utilisateur connecté
            $fullTranslation->setProfile($profile); // Enregistre les données de l'utilisateur connecté dans le champ du formulaire (setter)

            // Traitement du fichier
            if ($translationFile) {
                /* Utilise le service "FileUploader" (injecté dans la variable $fileUploader) pour envoyer le fichier contenu
                dans la variable $translationFile */
                $translationFileName = $fileUploader->upload($translationFile);

                // Ajoute le nom du fichier à la colonne "translationFileName"
                $fullTranslation->setTranslationFileName($translationFileName);

                // Ajoute l'extension du fichier à la colonne "translationFileExtension"
                $fullTranslation->setTranslationFileExtension(pathinfo($translation->getTranslationFileName(), PATHINFO_EXTENSION));
            }

            $em->persist($fullTranslation); // Crée le nouvel élément
            $em->flush(); // Exécute la requête (ici, elle ajoute la ligne dans la BDD)

            $this->addFlash('success', 'Traduction publiée avec succès.');

            return $this->redirectToRoute('profile_show');
        }

        return $this->render('translation/posts/newUpload.html.twig', [
            'translationForm' => $translationForm->createView(),
        ]);
    }

    #[IsGranted("ROLE_USER")]
    #[Route('translation/posts/edit/{id}', name: 'edit')]
    public function edit(Translation $translation, Request $request, EntityManagerInterface $em, FileUploader $fileUploader): Response
    {
        /* Je n'ai pas besoin de déclarer une nouvelle instance de la classe Translation car je veux modifier des données déjà
        existantes */
        $oldTranslationFile = $fileUploader->getTargetDirectory() . '/' . $translation->getTranslationFileName(); // Récupère le fichier actuellement lié à la traduction 
        $newTranslationFile = ""; // Initialise la variable newTranslationFile ici pour qu'elle soit accessible partout dans cette fonction

        // Initialisation du formulaire
        $editTranslationForm = $this->createForm(TranslationType::class, $translation, [
            'is_file_required' => false // Ici, le champ "translationFile" n'est pas requis
        ]);

        // Traitement du formulaire
        $editTranslationForm->handleRequest($request);

        // Vérifie si le formulaire est valide
        if ($editTranslationForm->isSubmitted() && $editTranslationForm->isValid()) {

            // Récupère la valeur du champ "translationFile" (le fichier) dans le formulaire
            $newTranslationFile = $editTranslationForm->get('translationFile')->getData();

            // Si un nouveau fichier est envoyé dans le formulaire
            if ($newTranslationFile) {
                unlink($oldTranslationFile); // Je supprime l'ancien fichier associé à la publication

                $newTranslationFileName = $fileUploader->upload($newTranslationFile);
                $translation->setTranslationFileName($newTranslationFileName);
                $translation->setTranslationFileExtension(pathinfo($translation->getTranslationFileName(), PATHINFO_EXTENSION));
            }

            $em->flush(); // Modifie la ligne en BDD

            $this->addFlash('success', 'Traduction modifiée avec succès.');

            return $this->redirectToRoute('profile_show');
        }

        return $this->render('translation/posts/edit.html.twig', [
            'editTranslationForm' => $editTranslationForm->createView()
        ]);
    }

    #[IsGranted("ROLE_USER")]
    #[Route('translation/posts/{id}/delete', name: 'delete', methods: ['POST'])]
    public function delete(#[CurrentUser] User $user, Translation $translation, EntityManagerInterface $em, Request $request, #[Autowire('%kernel.project_dir%/public/uploads/translations')] string $translationsDirectory): Response
    {
        // Si l'id du profil de l'utilisateur connecté n'est pas le même que l'identifiant du profil présent dans la traduction
        if ($user->getProfile()->getId() !== $translation->getProfile()->getId()) {
            throw new Exception("Suppression impossible.");
        } else {
            $submittedToken = $request->getPayload()->get('token'); // Récupère la valeur du champ nommé "token"
            $translationFile = $translationsDirectory . '/' . $translation->getTranslationFileName();

            // Si le CRSF est valide
            if ($this->isCsrfTokenValid('delete-item', $submittedToken)) {
                unlink($translationFile); // Supprime le fichier associé à la traduction

                $em->remove($translation); // Supprime la traduction
                $em->flush(); // Enregistre les changements

                $this->addFlash('success', 'Traduction supprimée avec succès.');

                return $this->redirectToRoute('profile_show');
            } else {
                throw new Exception('Erreur : Jeton CSRF invalide.'); // Si le jeton CSRF est invalide
            }
        }
    }
}
