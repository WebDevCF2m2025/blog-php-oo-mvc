<?php

use model\manager\CategoryManager;
use model\mapping\CategoryMapping;

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
    require_once RACINE_PATH."/view/home.html.php";
}