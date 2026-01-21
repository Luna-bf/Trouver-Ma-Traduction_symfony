<?php

namespace src\controllers;

// La classe HomeController hérite de la classe BaseController : elle récupère ses propriétés et méthodes
class HomeController extends BaseController { // Les méthodes de cette classe vont toutes appeler un fichier se trouvant dans le dossier "home"

    // J'appelle la méthode render() dans d'autres méthodes qui vont chacunes appeler le chemin d'accès d'un fichier spécifique
    public function landing() { // Cette méthode va appeler le fichier landing.php
        $this->render('home/landing.html.twig');
    }

    public function home() { // Cette méthode va appeler le fichier home.php
        $this->render('home/home.html.twig');
    }

    public function searchResult() { // ect...
        $this->render('home/search-result.html.twig');
    }

    public function profile() {
        $this->render('home/profile.html.twig');
    }

    // public function test() {
    //     $this->render('home/test.html.twig');
    // }
}