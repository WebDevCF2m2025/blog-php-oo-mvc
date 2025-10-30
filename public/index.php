<?php
// path: public/index.php

// typage strict
declare(strict_types=1);

// Autoload de composer (pour Twig, mais aussi toutes
// les bibliothèques tierces en PHP)
require_once "../vendor/autoload.php";

// démarrage de la session
session_start();

use Twig\Loader\FilesystemLoader;
use Twig\Environment;
// pour le debug de Twig
use Twig\Extension\DebugExtension;
// gestion du mailer avec Mailer
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;

// inclusion du fichier de configuration si config.php existe
if(file_exists("../config.php")){
    require_once "../config.php";
// sinon on prend la configuration originale
} else {
    require_once "../config.dev.php";
}



// on définit où se trouve nos templates
$loader = new FilesystemLoader(RACINE_PATH.'/view'); // dans view
// On lance le système de template de
// Twig en instanciant son environment
$twig = new Environment($loader, [
    // mode de débogage activé
    'debug' => true,
    //'cache' => '/path/to/compilation_cache',
]);
// on ajoute l'extension de debug
$twig->addExtension(new DebugExtension());


// création du transport des mails
$transport = Transport::fromDsn(DSN);
// instantiation d'un objet de type mail
$mailer = new Mailer($transport);

// exemple d'un template simple
//echo $twig->render('index.html.twig', ['name' => 'Fabien']);

// Autoload fonctionnel avec les namespaces personnels,
// ne fonctionne qu'en PHP Orienté Objet (fait main, on pourrait
// utiliser Composer pour y ajouter nos dépendances)
// et avec une arborescence de fichiers respectant les namespaces
spl_autoload_register(function ($class) {
    $class = str_replace('\\', '/', $class);
    require RACINE_PATH.'/' .$class . '.php';
});

// chargement du router
require_once RACINE_PATH."/controller/routerController.php";