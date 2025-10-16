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

    // appel de la vue
    require_once RACINE_PATH."/view/home.html.php";
}else{
    // autres pages
    $page = $_GET['pg'];
    switch ($page) {
        case "category":
            // page catégorie
            echo "<h2>Nous serons sur la page d'une catégorie</h2>";
            var_dump($_GET);
            break;
        case "article":
            // page article
            echo "<h2>Nous serons sur la page d'un article</h2>";
            var_dump($_GET);
            break;
        case "connection":
            // page connexion
            echo "<h2>Nous serons sur la page de connexion</h2>";
            var_dump($_GET);
            break;
        default:
            // page 404
            echo "<h1>404 - Page non trouvée</h1>";
            var_dump($_GET);
            break;
    }

}