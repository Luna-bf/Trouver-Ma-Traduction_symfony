<?php

/*
J'utilise un namespace pour renommer la classe en src\controllers, ça sert à éviter les conflits si j'importate une librairie
qui utilise aussi une classe nommée "BaseController" par exemple.
*/
namespace src\controllers; // Son nouveau nom est donc son chemin d'accès

use FilesystemIterator;
use src\model\Database; // Revoir pourquoi je ne met pas le nom du fichier (Database) lors de la déclaration du namespace
use \Twig\Environment; // Appel de la classe Environment
use Twig\Loader\FilesystemLoader; // Appel de la classe FilesystemLoader

// Le but de cette classe est fournir le chemin d'accès vers une page (template)
abstract class BaseController {

    protected $db;

    public function __construct()
    {
        // J'assigne la classe new Database (fichier Database.php) à la propriété $db
        $this->db = new Database(); // Je n'ai pas besoin de require ce fichier car j'ai l'auto-load dans le fichier index.php
    }

    /*
    La méthode render() appelle le chemin d'accès à une page : $path correspond au chemin d'accès du fichier et $data correspond
    à un tableau associatif qui va contenir les valeurs de la db (? à revoir pour $data)
    */
    public function render($path, $data = []) {
        
        /*
        J'implémente le code de twig afin de pouvoir l'utiliser et qu'il s'occupe lui-même du chargement des pages :
        
        Ici, j'indique le nom du dossier qui contient toutes mes pages (VIEWS) en tant qu'argument pour mon instance de
        la classe "FileSystemLoader" et je stocke cette instance dans une variable nommée $loader.
        
        Je déclare ensuite une variable nommée $twig, qui va contenir l'instance de la classe "Environment" : cette instance prend
        en argument la variable $loader, qui contient le nom du dossier où sont stockées toutes mes pages. Cette instance va donc
        charger tous les fichiers provenant de l'argument donné dans l'instance de la classe "FileSystemLoader" (dossier VIEWS).
        
        Enfin, je déclare une variable $template : elle va charger l'un des fichiers contenus dans la variable $twig grâce à la
        méthode load(), qui prend la variable $path en argument ($path correspond au chemin d'accès du fichier appelé).
        */
        $loader = new FilesystemLoader(VIEWS);
        $twig = new Environment($loader);
        $template = $twig->load($path);

        echo $template->render($data); // Cet echo va charger le fichier désiré
        // extract($data); // 
        // require_once VIEWS . "/$path"; // Le chemin d'accès démarre dans le dossier "views"
        exit;
    }
}