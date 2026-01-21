<?php

// Appel de la classe parent
namespace src\controllers;

// La classe HomeController hérite de la classe BaseController : elle récupère ses propriétés et méthodes
class PostController extends BaseController {

    // J'appelle la méthode render() dans d'autres méthodes qui vont chacunes appeler le chemin d'accès d'un fichier spécifique
    public function newUpload() { // Cette méthode va appeler le fichier new-upload.php
        $this->render('posts/new-upload.html.twig');
    }
}