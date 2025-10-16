<?php
// path: public/index.php

// typage strict
declare(strict_types=1);

// démarrage de la session
session_start();

use Twig\Loader\FilesystemLoader;
use Twig\Environment;

// inclusion du fichier de configuration
require_once '../config.dev.php';

// Autoload fonctionnel avec les namespaces personnels,
// ne fonctionne qu'en Orienté Objet
// et avec une arborescence de fichiers respectant les namespaces
spl_autoload_register(function ($class) {
    $class = str_replace('\\', '/', $class);
    require RACINE_PATH.'/' .$class . '.php';
});

// Autoload de composer (pour Twig, mais aussi toutes
// les bibliothèques tierces en PHP)
require_once RACINE_PATH."/vendor/autoload.php";

// on définit où se trouve nos templates
$loader = new FilesystemLoader(RACINE_PATH.'/view'); // dans view
// On lance le système de template de
// Twig en instanciant son environment
$twig = new Environment($loader, [
    //'cache' => '/path/to/compilation_cache',
]);

//echo $twig->render('index.html.twig', ['name' => 'Fabien']);

// chargement du router
require_once RACINE_PATH."/controller/routerController.php";