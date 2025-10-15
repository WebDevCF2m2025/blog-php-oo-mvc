<?php

use model\manager\CategoryManager;
use model\manager\ArticleManager;


// récupération des catégories pour le menu public
$categoryManager = new CategoryManager($connectPDO);
$categoriesMenu = $categoryManager->getCategoriesPublicMenu();

// homepage
if(empty($_GET['pg'])){

    // récupération des articles pour la homepage
    $articleManager = new ArticleManager($connectPDO);
    $articles = $articleManager->getArticlesHomepage();


    require_once RACINE_PATH."/view/home.html.php";
}else{
    // autres pages
    $page = $_GET['pg'];
    switch ($page) {
        case "category":
            // page catégorie
            require_once RACINE_PATH."/controller/categoryController.php";
            break;
        case "article":
            // page article
            require_once RACINE_PATH."/controller/articleController.php";
            break;
        case "connection":
            // page connexion
            require_once RACINE_PATH."/controller/connectionController.php";
            break;
        default:
            // page 404
            require_once RACINE_PATH."/view/404.html.php";
            break;
    }

}