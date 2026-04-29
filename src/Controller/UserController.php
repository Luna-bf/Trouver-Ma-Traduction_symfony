<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\TranslationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('/user', name: 'user_')]
final class UserController extends AbstractController
{
    #[Route('', name: 'index')]
    public function index(#[CurrentUser] User $user, TranslationRepository $repo): Response
    {
        $user_id = $user->getId(); // Récupère l'identifiant de l'utilisateur actuellement connecté
        $translations = $repo->findBy(['user' => $user_id]); // Je récupère les traductions associées à l'utilisateur
        $message = "";

        if ($translations === []) {
            $message = "Vous n'avez aucune traduction.";
        }

        return $this->render('user/index.html.twig', [
            'translations' => $translations,
            'message' => $message
        ]);
    }

    #[Route('/settings-pages/profile_settings', name: 'profile_settings')]
    public function profileSettings(): Response
    {
        return $this->render('user/settings-pages/profileSettings.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    #[Route('/settings-pages/profile_picture_settings', name: 'picture_settings')]
    public function profilePicture(): Response
    {
        return $this->render('user/settings-pages/uploadProfilePicture.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    #[Route('/settings-pages/preferences_settings', name: 'preferences_settings')]
    public function preferencesSettings(): Response
    {
        return $this->render('user/settings-pages/preferences.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    #[Route('/settings-pages/accessibility_settings', name: 'accessibility_settings')]
    public function accessibilitySettings(): Response
    {
        return $this->render('user/settings-pages/accessibility.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    #[Route('/settings-pages/account_settings', name: 'account_settings')]
    public function accountSettings(): Response
    {
        return $this->render('user/settings-pages/account.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }
}
