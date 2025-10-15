<?php

use model\manager\CategoryManager;
use model\mapping\CategoryMapping;
use model\manager\ArticleManager;
use model\mapping\ArticleMapping;
use model\mapping\UserMapping;

// homepage
if(empty($_GET['pg'])){
    // récupération des catégories pour le menu
    $categoryManager = new CategoryManager($connectPDO);
    $categories = $categoryManager->getCategories();
    $listCategories = [];
    foreach($categories as $category){
        $cat = new CategoryMapping($category);
        $listCategories[] = $cat;
    }
    // récupération des articles pour la homepage
    $articleManager = new ArticleManager($connectPDO);
    $articles = $articleManager->getArticles();
    $listArticles = [];
    foreach($articles as $article){
        $art = new ArticleMapping($article);
        // gestion de l'auteur de l'article
        $user = new UserMapping($article);
        $art->setUser($user);
        // gestion des catégories de l'article
        $cats = [];
        if(isset($article['category_slug'])){
            $arrSlug = explode("|||", $article['category_slug']);
            $arrTitle = explode("|||", $article['category_title']);
            for($i=0; $i<count($arrSlug); $i++){
                $c = new CategoryMapping([]);
                $c->setCategorySlug($arrSlug[$i]);
                $c->setCategoryTitle($arrTitle[$i]);
                $cats[] = $c;
            }
            $art->setCategories($cats);
        }
        $listArticles[] = $art;
    }

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