<?php
// path: public/index.php

// typage strict
declare(strict_types=1);

// démarrage de la session
session_start();

// inclusion du fichier de configuration
require_once '../config.dev.php';

// Autoload fonctionnel avec les namespaces personnels,
// ne fonctionne qu'en OO
spl_autoload_register(function ($class) {
    $class = str_replace('\\', '/', $class);
    require RACINE_PATH.'/' .$class . '.php';
});

// chargement du router
require_once RACINE_PATH."/controller/routerController.php";