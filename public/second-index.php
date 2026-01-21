<?php

// J'importe les classes à l'aide de leur namespace
use src\controllers\HomeController;
use src\controllers\FormController;
use src\controllers\FileController;
use src\controllers\PostController;
use src\controllers\SettingsController;

// Je défini le chemin d'accès de chaque dossier ici :
define('ROOT', dirname(__DIR__)); // dirname(__DIR__) va récupérer le nom du dossier parent (ici "Trouver-Ma-Traduction") et le stocker dans la constante nommée "ROOT"
define('VIEWS', ROOT . '/views'); //Je déclare une constante nommée "VIEWS" qui prend comme valeur la racine du projet concaténé avec la chaine de caractères "/views", ce qui donne le chemin complet suivant : racine_du_projet/views

require_once ROOT . "/vendor/autoload.php"; // Va charger toutes les librairies présentes dans le dossier vendors ainsi que les classes du dossier src. Cela remplace la fonction spl_autoload que j'avais déclaré avant (voir fichier spl-autoload.php dans le dossier src)

/* PATH_INFO correspond à (à développer) */
switch ($_SERVER['PATH_INFO'] ?? '/') { // J'utilise un switch pour gérer les différents appels de fichiers

    case '/': // Ce chemin défini landing.php comme page d'accueil, j'arriverais sur cette page lorsque je lancerais le serveur
        (new HomeController())->landing(); // Je créé un objet HomeController() et j'appelle la méthode landing() afin d'appeler le fichier landing.php
        break;

    // HomeController
    // case '/home/test':
    //     (new HomeController())->test();
    //     break;

    // FormController
    case '/form/signup': // Chemin d'accès : views/form/signup.php
        (new FormController())->signUp(); // Je créé un objet FormController() et j'appelle la méthode signUp() afin d'appeler le fichier signup.php
        break;

    case '/form/signin': // Chemin d'accès : views/form/signin.php
        (new FormController())->signIn(); // etc...
        break;

    // HomeController
    case '/home/home':
        (new HomeController())->home();
        break;

    case '/home/search-result':
        (new HomeController())->searchResult();
        break;

    case '/home/profile':
        (new HomeController())->profile();
        break;
        
    // FileController
    case '/file/file-viewer':
        (new FileController())->fileViewer();
        break;
        
    // PostController
    case '/posts/new-upload':
        (new PostController())->newUpload();
        break;
        
    // SettingsController
    case '/settings/profile-settings':
        (new SettingsController())->profileSettings();
        break;

    case '/settings/upload-profile-picture':
        (new SettingsController())->uploadProfilePicture();
        break;

    case '/settings/preferences':
        (new SettingsController())->preferences();
        break;

    case '/settings/accessibility':
        (new SettingsController())->accessibility();
        break;

    case '/settings/account':
        (new SettingsController())->account();
        break;

    default:
        echo "Page introuvable.";
        break;
}

?>