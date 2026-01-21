<?php

namespace App\Controller;

use App\Entity\Translation;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('', name: 'translation_')]
final class TranslationController extends AbstractController
{
    #[Route('', name: 'landing')]
    public function landing(): Response
    {
        return $this->render('translation/landing.html.twig', [
            'controller_name' => 'TranslationController',
        ]);
    }

    #[Route('form/register', name: 'register')]
    public function register(): Response
    {
        return $this->render('translation/form/register.html.twig', [
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
    public function home(): Response
    {
        return $this->render('translation/home/home.html.twig', [
            'controller_name' => 'TranslationController',
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
}
