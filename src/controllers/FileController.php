<?php

// Appel de la classe parent
namespace src\controllers;

// La classe FileController hérite de la classe BaseController : elle récupère ses propriétés et méthodes
class FileController extends BaseController { // Les méthodes de cette classe vont toutes appeler un fichier se trouvant dans le dossier "file"

    // J'appelle la méthode render() dans d'autres méthodes qui vont chacunes appeler le chemin d'accès d'un fichier spécifique
    public function fileViewer() { // Cette méthode va appeler le fichier file-viewer.php
        $this->render('file/file-viewer.html.twig');
    }
}