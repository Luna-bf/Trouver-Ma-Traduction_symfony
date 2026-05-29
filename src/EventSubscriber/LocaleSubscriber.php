<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

readonly class LocaleSubscriber implements EventSubscriberInterface {
    
    public function __construct(private readonly string $defaultLocale = 'fr') {}

    public function onKernelRequest(RequestEvent $event): void {
        $request = $event->getRequest();

        if(!$request->hasPreviousSession()) {
            return;
        }

        // Je vérifie si la langue est passée en paramètre (si l'utilisateur a changé la langue du site)
        if($locale = $request->query->get('_locale')) {
            // Si c'est le cas, je change la langue (ex: si l'utilisateur à saisi l'anglais, le site sera en anglais)
            $request->setLocale($locale);
        } else {
            // Sinon, j'affiche le site dans la langue par défaut
            $request->setLocale($request->getSession()->get('_locale', $this->defaultLocale));
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            // J'ajoute une priorité élevée à cette requête
            KernelEvents::REQUEST => [['onKernelRequest', 20]]
        ];
    }
}