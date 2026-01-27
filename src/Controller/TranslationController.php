<?php

namespace App\Controller;

use App\Entity\Translation;
use App\Form\TranslationType;
use App\Repository\TranslationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
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

    // Pour l'envoi de fichiers PDF : https://stackoverflow.com/questions/43001978/upload-pdf-file-with-symfony
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

            $translationFile = $translationForm->get('translationFileName')->getData();

            $fullTranslation = $translationForm->getData(); // revoir à quoi ça sert
            $fullTranslation->setCreatedAt(new \DateTimeImmutable()); // enregistre la date du jour
            $user = $this->getUser(); // récupère les données l'utilisateur connecté
            $fullTranslation->setUser($user); // enregistre les données de l'utilisateur connecté

            if ($translationFile) {
                $originalFilename = pathinfo($translationFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $translationFile->guessExtension();

                // Move the file to the directory where translations are stored
                try {
                    $translationFile->move($translationFile, $newFilename);
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'translationFilename' property to store the PDF file name instead of its contents
                $fullTranslation->setTranslationFilename($newFilename);
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

    #[Route('translation/posts/show/{id}', name: 'show')]
    public function show(Translation $translation): Response
    {
        return $this->render('translation/posts/show.html.twig', [
            'translation' => $translation
        ]);
    }

    #[Route('translation/posts/edit/{id}', name: 'edit')]
    public function edit(Translation $translation, Request $request, EntityManagerInterface $em, SluggerInterface $slugger, #[Autowire('%kernel.project_dir%/public/uploads/translations')] string $translationsDirectory): Response
    {
        // Initialisation du formulaire
        $translationForm = $this->createForm(TranslationType::class, $translation);

        // Traitement du formulaire
        $translationForm->handleRequest($request);

        // Vérifie si le formulaire est valide
        if ($translationForm->isSubmitted() && $translationForm->isValid()) {

            $translationFile = $translationForm->get('translationFileName')->getData();

            // Si un fichier est envoyé dans le formulaire
            if ($translationFile) {
                $originalFilename = pathinfo($translationFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $translationFile->guessExtension();

                // Move the file to the directory where translations are stored
                try {
                    $translationFile->move($translationFile, $newFilename);
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                    $e = "Une erreur est survenue lors de l'envoi de la traduction, veuillez réessayer.";
                }

                // updates the 'translationFilename' property to store the PDF file name instead of its contents
                $translation->setTranslationFilename($newFilename);
            }

            $em->persist($translation);
            $em->flush();

            $this->addFlash('success', 'Traduction modifiée avec succès.');

            return $this->redirectToRoute('user_index', ["id" => $translation->getId()]);
        }

        return $this->render('translation/posts/edit.html.twig', [
            'translationForm' => $translationForm->createView()
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
