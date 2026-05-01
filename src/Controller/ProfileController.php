<?php

namespace App\Controller;

use App\Entity\Profile;
use App\Entity\User;
use App\Form\ProfileType;
use App\Repository\TranslationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('/profile', name: 'profile_')]
final class ProfileController extends AbstractController
{
    #[Route('', name: 'create')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $profile = new Profile();
        $user = $this->getUser();

        $profileForm = $this->createForm(ProfileType::class, $profile);

        $profileForm->handleRequest($request);

        if($profileForm->isSubmitted() && $profileForm->isValid()) {

            $fullProfile = $profileForm->getData();
            $fullProfile->setUser($user);

            $em->persist($profile);
            $em->flush();

            return $this->redirectToRoute('translation_home');
        }

        return $this->render('profile/form/create.html.twig', [
            'profileForm' => $profileForm,
        ]);
    }

    #[Route('/myProfile', name: 'show')]
    public function profile(#[CurrentUser] User $user, TranslationRepository $repo): Response
    {
        $user_id = $user->getId(); // Récupère l'identifiant de l'utilisateur actuellement connecté
        $translations = $repo->findBy(['user' => $user_id]); // Je récupère les traductions associées à l'utilisateur
        $profile = $user->getProfile(); // Récupère le nom de l'utilisateur à partir de l'entité User (ici, l'utilisateur actuellement connecté)
        $message = "";

        if ($translations === []) {
            $message = "Vous n'avez aucune traduction.";
        }

        return $this->render('profile/index.html.twig', [
            'translations' => $translations,
            'profile' => $profile,
            'message' => $message
        ]);
    }
}
