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
            var_dump($_GET);
            break;
        case "article":
            // page article
            var_dump($_GET);
            break;
        case "connection":
            // page connexion
            var_dump($_GET);
            break;
        default:
            // page 404
            echo "<h1>404 - Page non trouvée</h1>";
            var_dump($_GET);
            break;
    }

}