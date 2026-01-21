<?php

namespace App\Controller;

use App\Entity\Profile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/profile', name: 'profile_')]
final class ProfileController extends AbstractController
{
    #[Route('', name: 'index')]
    public function index(): Response
    {
        return $this->render('profile/index.html.twig', [
            'controller_name' => 'ProfileController',
        ]);
    }

    // #[Route('/posts/new_upload', name: 'new_upload')]
    // public function newUpload(): Response
    // {
    //     return $this->render('profile/posts/newUpload.html.twig', [
    //         'controller_name' => 'ProfileController',
    //     ]);
    // }

    #[Route('/settings-pages/profile_settings', name: 'profile_settings')]
    public function profileSettings(): Response
    {
        return $this->render('profile/settings-pages/profileSettings.html.twig', [
            'controller_name' => 'ProfileController',
        ]);
    }

    #[Route('/settings-pages/profile_picture_settings', name: 'picture_settings')]
    public function profilePicture(): Response
    {
        return $this->render('profile/settings-pages/uploadProfilePicture.html.twig', [
            'controller_name' => 'ProfileController',
        ]);
    }

    #[Route('/settings-pages/preferences_settings', name: 'preferences_settings')]
    public function preferencesSettings(): Response
    {
        return $this->render('profile/settings-pages/preferences.html.twig', [
            'controller_name' => 'ProfileController',
        ]);
    }

    #[Route('/settings-pages/accessibility_settings', name: 'accessibility_settings')]
    public function accessibilitySettings(): Response
    {
        return $this->render('profile/settings-pages/accessibility.html.twig', [
            'controller_name' => 'ProfileController',
        ]);
    }

    #[Route('/settings-pages/account_settings', name: 'account_settings')]
    public function accountSettings(): Response
    {
        return $this->render('profile/settings-pages/account.html.twig', [
            'controller_name' => 'ProfileController',
        ]);
    }
}
