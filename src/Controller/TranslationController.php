<?php

namespace App\Controller;

use App\Entity\Translation;
use App\Form\TranslationType;
use App\Repository\TranslationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
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

    // Pour l'envoi de fichiers PDF : https://symfony.com/doc/current/controller/upload_file.html
    #[Route('/posts/new_upload', name: 'new_upload')]
    public function newUpload(Request $request, EntityManagerInterface $em, SluggerInterface $slugger, #[Autowire('%kernel.project_dir%/public/uploads/translations')] string $translationsDirectory): Response
    {
        // Création d'une nouvelle instance de Translation
        $translation = new Translation();

        // Initialisation du formulaire
        $translationForm = $this->createForm(TranslationType::class, $translation, [
            'is_file_required' => true // Ici, le champ "translation_file_name" est requis
        ]);

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

            $em->persist($fullTranslation); // Crée le nouvel élément
            $em->flush(); // Exécute la requête (ici, elle ajoute la ligne dans la BDD)

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
        $oldTranslationFile = $translationsDirectory . '/' . $translation->getTranslationFileName();
        $newTranslationFile = "";

        // Je n'ai pas besoin de déclarer une nouvelle instance de la classe Translation car je veux modifier des données déjà existantes
        // Initialisation du formulaire
        $editTranslationForm = $this->createForm(TranslationType::class, $translation, [
            'is_file_required' => false // Ici, le champ "translation_file_name" n'est pas requis
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

                // Puis je relie le nouveau fichier à la publication
                $originalFilename = pathinfo($newTranslationFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $newTranslationFile->guessExtension();

                $newTranslationFile->move($translationsDirectory, $newFilename);
                $translation->setTranslationFileName($newFilename);
            }

            $em->flush(); // Modifie la ligne en BDD

            $this->addFlash('success', 'Traduction modifiée avec succès.');

            return $this->redirectToRoute('user_index');
        }

        return $this->render('translation/posts/edit.html.twig', [
            'editTranslationForm' => $editTranslationForm->createView()
        ]);
    }

    #[Route('translation/posts/{id}/delete', name: 'delete', methods: ['POST'])]
    #[IsGranted("ROLE_USER")]
    public function delete(Translation $translation, EntityManagerInterface $em, Request $request, #[Autowire('%kernel.project_dir%/public/uploads/translations')] string $translationsDirectory): Response
    {
        // Si l'id de l'utilisateur connecté n'est pas le même que l'identifiant présent dans la traduction
        if ($this->getUser() !== $translation->getUser()) {
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
                return $this->redirectToRoute('user_index');
            }
        }

        throw new Exception('Erreur : Formulaire invalide.'); // Si le CSRF est invalide
    }
}
