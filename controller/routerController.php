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
// si l'utilisateur est connecté
if(isset($_SESSION["user_id"],$_SESSION["role_name"])){

    // et que c'est un admin
    if($_SESSION["role_name"] === "Admin"){

        // Contrôleur partie admin
        require_once RACINE_PATH . "/controller/adminController.php";

        // sinon TO DO
    }else {

        var_dump($_SESSION);

    }

}else{
    // Contrôleur partie publique
    require_once RACINE_PATH . "/controller/publicController.php";
}



$connectPDO = null;