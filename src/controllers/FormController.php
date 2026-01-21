<?php

namespace src\controllers;

// La classe FormController hérite de la classe BaseController : elle récupère ses propriétés et méthodes
class FormController extends BaseController {

    // J'appelle la méthode render() dans d'autres méthodes qui vont chacunes appeler le chemin d'accès d'un fichier spécifique
    public function signIn() { // Cette méthode va appeler le fichier signin.php
        $this->render('form/signin.html.twig'); // J'assigne le chemin d'accès au fichier à la propriété $path
    }

    public function signUp() { // Cette méthode va appeler le fichier signup.php
        $this->render('form/signup.html.twig');
    }
}