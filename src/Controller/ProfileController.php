<?php

namespace App\Controller;

use App\Entity\Profile;
use App\Entity\User;
use App\Form\ProfileType;
use App\Repository\TranslationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;

#[IsGranted("ROLE_USER")]
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

        if ($profileForm->isSubmitted() && $profileForm->isValid()) {

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
    public function profileSettings(#[CurrentUser] User $user, Request $request, EntityManagerInterface $em, SluggerInterface $slugger, #[Autowire('%kernel.project_dir%/public/uploads/profile/profilePictures')] string $profilePictures, #[Autowire('%kernel.project_dir%/public/uploads/profile/profileThumbnails')] string $profileThumbnails): Response
    {
        // Récupère le profil de l'utilisateur
        $profile = $user->getProfile();

        // Photo de profil
        $oldProfilePicture = $profilePictures . '/' . $profile->getProfilePictureName();
        $newProfilePicture = "";

        // Bannière de profil
        $oldThumbnail = $profilePictures . '/' . $profile->getThumbnailName();
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

                    $originalFilename = pathinfo($newProfilePicture->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeFilename = $slugger->slug($originalFilename);
                    $newFilename = $safeFilename . '-' . uniqid() . '.' . $newProfilePicture->guessExtension();

                    try {
                        $newProfilePicture->move($profilePictures, $newFilename);
                    } catch (FileException $e) {
                        $message = $e;
                    }

                    // Met à jour le nom du fichier dans la BDD
                    $profile->setProfilePictureName($newFilename);

                    // Sinon, si je récupère un fichier :
                } else {
                    // Je supprime l'ancien fichier
                    unlink($oldProfilePicture);

                    /*
                    Puis j'ajoute le nouveau fichier :
                    
                    Je commence par récupérer le nom original du fichier : la fonction pathinfo() prend pour paramètre :
                    - La variable $newProfilePicture (soit le fichier) et son chemin d'accès
                    */
                    $originalFilename = pathinfo($newProfilePicture->getClientOriginalName(), PATHINFO_FILENAME);

                    /*
                    Ensuite, je sécurise le nom du fichier grâce au composant SluggerInterface : celui-ci permet de créer une
                    chaîne de caractère contenant uniquement des caractères sécurisés.
                    */
                    $safeFilename = $slugger->slug($originalFilename);
                    /*
                    Enfin, je crée un nouveau nom pour le fichier :
                    
                    - J'utilise la chaîne de caractère de la variable $safeFileName ainsi qu'un tiret (-)
                    - J'ajoute un identifiant unique au fichier (au cas où deux fichiers auraient le même nom) avec la fonction
                    "uniqid()"
                    - J'ajoute ensuite un point (pour l'extension du fichier)
                    - Enfin, j'utilise la fonction guessExtension() pour obtenir l'extension du fichier
                    */
                    $newFilename = $safeFilename . '-' . uniqid() . '.' . $newProfilePicture->guessExtension();

                    try {
                        // Je déplace le fichier dans le dossier adéquat (profilePictures) sous son nouveau nom (newFileName)
                        $newProfilePicture->move($profilePictures, $newFilename);
                    } catch (FileException $e) {
                        $message = $e;
                    }

                    // Met à jour le nom du fichier dans la BDD
                    $profile->setProfilePictureName($newFilename);
                }
            }
            
            // Si le champ "thumbnailName" contient un fichier (si il n'est pas vide)
            if ($newThumbnail) {

                /*
                Je vérifie ce qui est récupéré, si l'utilisateur n'a pas encore de photo de profil (valeur NULL dans la BDD),
                alors cela signifie que le chemin récupéré s'arrête au dossier "profileThumbnails", j'utilise donc la fonction
                "is_dir()" pour vérifier que le contenu récupéré est bien un dossier, puis j'envoie le fichier dans celui-ci
                sans utiliser la méthode "unlink()", car je n'ai pas besoin de supprimer une ancienne bannière de profil.
                */
                if (is_dir($oldThumbnail)) {

                    $originalFilename = pathinfo($newThumbnail->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeFilename = $slugger->slug($originalFilename);
                    $newFilename = $safeFilename . '-' . uniqid() . '.' . $newThumbnail->guessExtension();

                    try {
                        $newThumbnail->move($profilePictures, $newFilename);
                    } catch (FileException $e) {
                        $message = $e;
                    }

                    // Met à jour le nom du fichier dans la BDD
                    $profile->setThumbnailName($newFilename);

                    // Sinon, si je récupère un fichier :
                } else {
                    // Je supprime l'ancien fichier
                    unlink($oldThumbnail);

                    /*
                    Puis j'ajoute le nouveau fichier :
                    
                    Je commence par récupérer le nom original du fichier : la fonction pathinfo() prend pour paramètre :
                    - La variable $newThumbnail (soit le fichier) et son chemin d'accès
                    */
                    $originalFilename = pathinfo($newThumbnail->getClientOriginalName(), PATHINFO_FILENAME);

                    /*
                    Ensuite, je sécurise le nom du fichier grâce au composant SluggerInterface : celui-ci permet de créer une
                    chaîne de caractère contenant uniquement des caractères sécurisés.
                    */
                    $safeFilename = $slugger->slug($originalFilename);
                    /*
                    Enfin, je crée un nouveau nom pour le fichier :
                    
                    - J'utilise la chaîne de caractère de la variable $safeFileName ainsi qu'un tiret (-)
                    - J'ajoute un identifiant unique au fichier (au cas où deux fichiers auraient le même nom) avec la fonction
                    "uniqid()"
                    - J'ajoute ensuite un point (pour l'extension du fichier)
                    - Enfin, j'utilise la fonction guessExtension() pour obtenir l'extension du fichier
                    */
                    $newFilename = $safeFilename . '-' . uniqid() . '.' . $newThumbnail->guessExtension();

                    try {
                        // Je déplace le fichier dans le dossier adéquat (profileThumbnails) sous son nouveau nom (newFileName)
                        $newThumbnail->move($profileThumbnails, $newFilename);
                    } catch (FileException $e) {
                        $message = $e;
                    }

                    // Met à jour le nom du fichier dans la BDD
                    $profile->setThumbnailName($newFilename);
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
}
