<?php
// path: controller/routerController.php

use model\mapping\UserMapping;

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
$user1 = new UserMapping([
    'user_login' => "Mikhawa",
    'user_real_name'=> "Michaël Pitz",
]);

include RACINE_PATH."/view/home.html.php";



$connectPDO = null;