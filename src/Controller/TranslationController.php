<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('', name: 'translation_')]
final class TranslationController extends AbstractController
{
    #[Route('', name: 'landing')]
    public function index(): Response
    {
        return $this->render('translation/index.html.twig', [
            'controller_name' => 'TranslationController',
        ]);
    }
}
