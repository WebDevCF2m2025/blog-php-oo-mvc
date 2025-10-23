<?php
// path: controller/routerController.php


# Connexion PDO
try {
    $connectPDO = new PDO(
        DB_TYPE . ':host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET,
        DB_LOGIN,
        DB_PWD,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (Exception $e) {
    die($e->getMessage());
}

// si nous sommes connecté en tant qu'administrateur et que nous avons cliqué sur la partie administration
if (isset($_SESSION['role_name']) && $_SESSION['role_name'] === 'Admin' && isset($_GET['pg']) && $_GET['pg'] === 'admin') {
    // on charge le contrôleur admin
    require_once RACINE_PATH . "/controller/adminController.php";
}else{
    // Pour le moment, nous chargeons toujours le contrôleur public
    require_once RACINE_PATH . "/controller/publicController.php";
}
// Bonne pratique
$connectPDO = null;