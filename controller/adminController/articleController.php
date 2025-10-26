<?php
// path: controller/adminController/articleController.php

use model\manager\ArticleManager;
use model\manager\CategoryManager;
use model\manager\UserManager;
use model\mapping\ArticleMapping;
use model\mapping\CategoryMapping;

// on récupère l'action
$action = $_GET['id'] ?? 'list';

// on instancie les managers
$articleManager = new ArticleManager($connectPDO);
$categoryManager = new CategoryManager($connectPDO);
$userManager = new UserManager($connectPDO);

$categoriesMenu = $categoryManager->getCategoriesPublicMenu();

switch($action){
    case 'add':
        // si on a soumis le formulaire
        if(!empty($_POST)){
            try{
                // on crée un nouvel article
                $article = new ArticleMapping($_POST);
                // si il existe au moins 1 catégorie
                if(isset($_POST['category_category_id'])) {
                    // on récupère les catégories
                    $categories = [];
                    foreach ($_POST['category_category_id'] as $id) {
                        $categories[] = new CategoryMapping(['category_id' => $id]);
                    }
                    // on ajoute les catégories à l'article
                    $article->setCategories($categories);
                }
                // on insère l'article
                $insert = $articleManager->createArticle($article);
                if($insert){
                    // on redirige vers la liste des articles
                    header("Location: " . RACINE_URL . "admin/article/?comment=success");
                    exit();
                }else{
                    $error = "Erreur lors de l'insertion de l'article";
                }
            }catch(Exception $e){
                $error = $e->getMessage();
            }
        } 

        // on récupère toutes les catégories
        $categories = $categoryManager->getAllCategories();
        // on récupère tous les utilisateurs
        $users = $userManager->getAllUsers();


        // on affiche la vue
        echo $twig->render('backend/add.article.back.html.twig', [
            'racineURL' => RACINE_URL,
            'categories' => $categories,
            'users' => $users,
            'session' => $_SESSION,
        ]);
        break;
    // par défaut, on affiche la liste des articles
    default:
        // TODO : créer la vue et la logique pour lister les articles
        echo "<h1>Liste des articles (à faire)</h1>";
        break;
}