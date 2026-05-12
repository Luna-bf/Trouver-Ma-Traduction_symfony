<?php

namespace App\Controller;

use App\Entity\Profile;
use App\Entity\User;
use App\Form\ProfileType;
use App\Repository\TranslationRepository;
use App\Service\ProfilePictureUploader;
use App\Service\ThumbnailUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted("ROLE_USER")]
#[Route('/profile', name: 'profile_')]
final class ProfileController extends AbstractController
{
    #[Route('/create', name: 'create')]
    public function index(Request $request, EntityManagerInterface $em, ProfilePictureUploader $profilePictureUploader, ThumbnailUploader $thumbnailUploader): Response
    {
        $profile = new Profile();
        $user = $this->getUser();

        $profileForm = $this->createForm(ProfileType::class, $profile);

        $profileForm->handleRequest($request);

        if ($profileForm->isSubmitted() && $profileForm->isValid()) {

            $profilePicture = $profileForm->get('profilePictureName')->getData(); // Récupère la valeur de "profilePictureName"
            $thumbnail = $profileForm->get('thumbnailName')->getData(); // Récupère la valeur de "thumbnailName"

            $fullProfile = $profileForm->getData(); // Récupère toutes les données du formulaire
            $fullProfile->setUser($user);

            // Traitement de la photo de profil
            if ($profilePicture) {
                $profilePictureFileName = $profilePictureUploader->upload($profilePicture);
                $fullProfile->setProfilePictureName($profilePictureFileName);
            }

            // Traitement de la bannière de profil
            if ($thumbnail) {
                $thumbnailFileName = $thumbnailUploader->upload($thumbnail);
                $fullProfile->setThumbnailName($thumbnailFileName);
            }

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
        $profile = $user->getProfile(); // Récupère le profil de l'utilisateur à partir de l'entité User (l'utilisateur actuellement connecté)      
        $translations = $repo->findBy(['profile' => $profile]); // Je récupère les traductions associées à l'utilisateur
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

    #[Route('/settings/profileSettings', name: 'settings')]
    public function profileSettings(#[CurrentUser] User $user, Request $request, EntityManagerInterface $em, ProfilePictureUploader $profilePictureUploader, ThumbnailUploader $thumbnailUploader): Response
    {
        // Récupère le profil de l'utilisateur
        $profile = $user->getProfile();

        // Photo de profil
        $oldProfilePicture = $profilePictureUploader->getTargetDirectory() . '/' . $profile->getProfilePictureName();
        $newProfilePicture = "";

        // Bannière de profil
        $oldThumbnail = $thumbnailUploader->getTargetDirectory() . '/' . $profile->getThumbnailName();
        $newThumbnail = "";

        // Initialisation du formulaire
        $editProfileForm = $this->createForm(ProfileType::class, $profile);

        $editProfileForm->handleRequest($request);

        if ($editProfileForm->isSubmitted() && $editProfileForm->isValid()) {

            // Récupère la valeur des champs "profilePictureName" et "thumbnailName"
            $newProfilePicture = $editProfileForm->get('profilePictureName')->getData();
            $newThumbnail = $editProfileForm->get('thumbnailName')->getData();

            // Si le champ "profilePictureName" contient un fichier (si il n'est pas vide)
            if ($newProfilePicture) {

                /*
                Je vérifie ce qui est récupéré, si l'utilisateur n'a pas encore de photo de profil (valeur NULL dans la BDD),
                alors cela signifie que le chemin récupéré s'arrête au dossier "profilePictures", j'utilise donc la fonction
                "is_dir()" pour vérifier que le contenu récupéré est bien un dossier, puis j'envoie le fichier dans celui-ci
                sans utiliser la méthode "unlink()", car je n'ai pas besoin de supprimer une ancienne photo de profil.
                */
                if (is_dir($oldProfilePicture)) {
                    
                    $newProfilePictureFileName = $profilePictureUploader->upload($newProfilePicture);
                    $profile->setProfilePictureName($newProfilePictureFileName);

                // Sinon, si je récupère un fichier :
                } else {
                    unlink($oldProfilePicture); // Je supprime l'ancien fichier

                    $newProfilePictureFileName = $profilePictureUploader->upload($newProfilePicture);
                    $profile->setProfilePictureName($newProfilePictureFileName);
                }
            }
            
            // Si le champ "thumbnailName" contient un fichier (si il n'est pas vide)
            if ($newThumbnail) {

                /*
                Je vérifie ce qui est récupéré, si l'utilisateur n'a pas encore de bannière de profil (valeur NULL dans la BDD),
                alors cela signifie que le chemin récupéré s'arrête au dossier "thumbnails", j'utilise donc la fonction
                "is_dir()" pour vérifier que le contenu récupéré est bien un dossier, puis j'envoie le fichier dans celui-ci
                sans utiliser la méthode "unlink()", car je n'ai pas besoin de supprimer une ancienne bannière de profil.
                */
                if (is_dir($oldThumbnail)) {
                    
                    $thumbnailFileName = $thumbnailUploader->upload($newThumbnail);
                    $profile->setThumbnailName($thumbnailFileName);

                // Sinon, si je récupère un fichier :
                } else {
                    unlink($oldThumbnail); // Je supprime l'ancien fichier

                    $thumbnailFileName = $thumbnailUploader->upload($newThumbnail);
                    $profile->setThumbnailName($thumbnailFileName);
                }
            }

            $em->flush(); // Modifie la ligne en BDD

            $this->addFlash('success', 'Profil mis à jour avec succès.');

            return $this->redirectToRoute('profile_show');
        }

        return $this->render('profile/settings/profile.html.twig', [
            'editProfileForm' => $editProfileForm,
            'profile' => $profile
        ]);
    }

    #[Route('/settings/preferences_settings', name: 'preferences_settings')]
    public function preferencesSettings(#[CurrentUser] User $user): Response
    {
        $profile = $user->getProfile();

        return $this->render('profile/settings/preferences.html.twig', [
            'profile' => $profile
        ]);
    }

    #[Route('/settings/accessibility_settings', name: 'accessibility_settings')]
    public function accessibilitySettings(#[CurrentUser] User $user): Response
    {
        $profile = $user->getProfile();

        return $this->render('profile/settings/accessibility.html.twig', [
            'profile' => $profile
        ]);
    }
}
