<?php

namespace App\Controller;

use App\Entity\Profile;
use App\Entity\User;
use App\Repository\TranslationRepository;
use App\Service\FileUploader;
use App\Service\ProfilePictureUploader;
use App\Service\ThumbnailUploader;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted("ROLE_USER")]
#[Route('/user', name: 'user_')]
final class UserController extends AbstractController
{
    #[Route('/settings/account_settings', name: 'account_settings')]
    public function accountSettings(#[CurrentUser] User $user): Response
    {
        $profile = $user->getProfile();

        return $this->render('user/account.html.twig', [
            'profile' => $profile
        ]);
    }

    #[Route('/settings/account_settings/delete/{id}', name: 'account_delete')]
    public function accountDelete(#[CurrentUser] User $user, Profile $profile, TranslationRepository $repo, Request $request, EntityManagerInterface $em, FileUploader $fileUploader, ProfilePictureUploader $profilePictureUploader, ThumbnailUploader $thumbnailUploader, TokenStorageInterface $tokenStorage): Response
    {
        if ($user->getId() !== $profile->getId()) {
            throw new Exception("Suppression impossible.");
        } else {
            $submittedToken = $request->getPayload()->get('token'); // Récupère la valeur du champ nommé "token"
            
            // Récupère toutes les traductions d'un utilisateur grâce à l'identifiant du profil auxquelles elles sont associées
            $translations = $repo->findBy(['profile' => $user->getProfile()->getId()]); // Récupère un objet Translation
            $profilePicture = $profilePictureUploader->getTargetDirectory() . '/' . $profile->getProfilePictureName();
            $thumbnail = $thumbnailUploader->getTargetDirectory() . '/' . $profile->getThumbnailName();

            // Si le jeton CRSF est valide
            if ($this->isCsrfTokenValid('delete-account', $submittedToken)) {

                if ($translations !== []) {

                    foreach ($translations as $translation) {
                        unlink($fileUploader->getTargetDirectory() . '/' . $translation->getTranslationFileName());
                        $em->remove($translation);
                    }
                }

                // Si la valeur récupérée est un fichier (si l'utilisateur possède une photo de profil)
                if (is_file($profilePicture)) {

                    unlink($profilePicture);
                }

                // Si la valeur récupérée est un fichier (si l'utilisateur possède une bannière de profil)
                if (is_file($thumbnail)) {

                    unlink($thumbnail);
                }

                $em->remove($profile);
                $em->flush();

                // Déconnecte complètement l'utilisateur en supprimant son jeton (token) d'authentification
                $tokenStorage->setToken(null);

                return $this->redirectToRoute('app_register');
            } else {
                throw new Exception('Erreur : Le jeton CSRF est invalide.'); // Si le jeton CSRF est invalide
            }
        }
    }
}
