<?php

namespace App\Controller;

use App\Entity\Translation;
use App\Form\TranslationType;
use App\Repository\TranslationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

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

    #[Route('/posts/new_upload', name: 'new_upload')]
    public function newUpload(Request $request, EntityManagerInterface $em): Response
    {
        // Création d'une nouvelle instance de Translation
        $translation = new Translation();

        // Initialisation du formulaire
        $translationForm = $this->createForm(TranslationType::class, $translation);

        // Traitement du formulaire
        $translationForm->handleRequest($request);

        // Vérifie si le formulaire est valide
        if ($translationForm->isSubmitted() && $translationForm->isValid()) {
            
            $fullTranslation = $translationForm->getData(); // revoir à quoi ça sert
            $fullTranslation->setCreatedAt(new \DateTimeImmutable()); // enregistre la date du jour
            $user = $this->getUser(); // récupère les données l'utilisateur connecté
            $fullTranslation->setUser($user); // enregistre les données de l'utilisateur connecté

            $em->persist($fullTranslation); // Prépare la requête
            $em->flush(); // Exécute la requête

            $this->addFlash('success', 'Traduction publiée avec succès.');

            return $this->redirectToRoute('user_index');
        }

        return $this->render('translation/posts/newUpload.html.twig', [
            'translationForm' => $translationForm->createView(),
        ]);
    }
}
