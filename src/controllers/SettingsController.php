<?php

// Appel de la classe parent
namespace src\controllers;

// La classe SettingsController hérite de la classe BaseController : elle récupère ses propriétés et méthodes
class SettingsController extends BaseController {

    // J'appelle la méthode render() dans d'autres méthodes qui vont chacunes appeler le chemin d'accès d'un fichier spécifique
    public function profileSettings() { // Cette méthode va appeler le fichier profile-settings.php
        $this->render('settings-pages/profile-settings.html.twig');
    }

    public function uploadProfilePicture() { // Cette méthode va appeler le fichier upload-profile-picture.php
        $this->render('settings-pages/upload-profile-picture.html.twig');
    }

    public function preferences() { // etc...
        $this->render('settings-pages/preferences.html.twig');
    }

    public function accessibility() {
        $this->render('settings-pages/accessibility.html.twig');
    }

    public function account() {
        $this->render('settings-pages/account.html.twig');
    }
}