<?php
// path: controller/routerController.php


use model\mapping\RoleMapping;
use model\mapping\UserMapping;
use model\manager\UserManager;


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
    'user_id'=>null,
    'user_login' => "Mikhawa",
    'user_real_name'=> "Michaël Pitz",
    'user_hidden_id'=> uniqid("my_blog",true),
    'user_activate' => true,
]);
$user2 = new UserMapping([]);

$role1 = new RoleMapping([]);

$userManager = new UserManager($connectPDO);

include RACINE_PATH."/view/home.html.php";



$connectPDO = null;