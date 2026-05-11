<?php

namespace App\Security;

use App\Entity\User;
use Override;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Doctrine\ORM\EntityManagerInterface;

class LoginFormAuthenticator extends AbstractLoginFormAuthenticator
{
    public function __construct(private EntityManagerInterface $entityManager) {}

    protected function getLoginUrl(Request $request): string
    {
        return '/login';
    }

    public function authenticate(Request $request): Passport
    {
        $email = $request->request->get('email');

        if (null === $email) {
            throw new \InvalidArgumentException('Email cannot be null');
        }
        $password = $request->request->get('password');

        return new Passport(
            new UserBadge($email, function ($userIdentifier) {
                // optionally pass a callback to load the User manually
                $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $userIdentifier]);

                if (!$user) {
                    throw new UserNotFoundException();
                }

                return $user;
            }),
            new PasswordCredentials($password),
            [
                new CsrfTokenBadge(
                    'authenticate',
                    $request->request->get('_csrf_token')
                ),
            ]
        );
    }

    // #[Override]
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        $user = $token->getUser();

        if ($user->getProfile() === null) {
            return new RedirectResponse('profile/create');
        } else {
            return new RedirectResponse('home');
        }
    }
}
