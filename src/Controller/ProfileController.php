<?php

namespace App\Controller;

use App\Entity\Profile;
use App\Form\ProfileType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/profile', name: 'profile_')]
final class ProfileController extends AbstractController
{
    #[Route('', name: 'create')]
    public function index(Request $request): Response
    {
        $profile = new Profile();

        $profileForm = $this->createForm(ProfileType::class, $profile);

        return $this->render('profile/index.html.twig', [
            'profileForm' => $profileForm,
        ]);
    }
}
