<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\TranslationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted("ROLE_USER")]
#[Route('/user', name: 'user_')]
final class UserController extends AbstractController
{
    #[Route('/settings-pages/account_settings', name: 'account_settings')]
    public function accountSettings(): Response
    {
        return $this->render('user/settings-pages/account.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }
}
