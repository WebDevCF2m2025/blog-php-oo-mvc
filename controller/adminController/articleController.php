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
    // liste des articles
    case 'list':
        // on récupère tous les articles
        $articles = $articleManager->getAllArticles();
        // on affiche la vue
        echo $twig->render("backend/list.article.html.twig",[
            'racineURL' => RACINE_URL,
            'articles' => $articles,
            'session' => $_SESSION,
        ]);
    break;
    // ajout d'un article
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
    // suppresion d'un article
    case 'delete':
        if(isset($_GET['ident'])) {
            // on récupère l'id de l'article
            $id = $_GET['ident'];
            // on supprime l'article
            $delete = $articleManager->deleteArticle($id);
            if($delete){
                // on redirige vers la liste des articles
                header("Location: " . RACINE_URL . "admin/article/?comment=success");
                exit();
            }else{
                // message d'erreur
                $error = "Erreur lors de la suppression de l'article";
                echo $twig->render("backend/error.404.back.html.twig", ['racineURL' => RACINE_URL, 'session' => $_SESSION ?? [], 'error' => $error]);
            }

        }else{
            // message d'erreur
            $error = "Erreur lors de la suppression de l'article";

            echo $twig->render("backend/error.404.back.html.twig", ['racineURL' => RACINE_URL, 'session' => $_SESSION ?? [], 'error' => $error]);
        }
        break;
        // modification d'un article
    case 'update':
        // si on a soumis le formulaire
        if(!empty($_POST)) {

        }else{
            // on récupère l'id de l'article
            $id = $_GET['ident'];
            // on récupère l'article
            $article = $articleManager->getArticleById($id);
            // on récupère toutes les catégories
            $categories = $categoryManager->getAllCategories();
            // on récupère tous les utilisateurs
            $users = $userManager->getAllUsers();

            // on récupère la vue
            echo $twig->render("backend/update.article.back.html.twig", [
                'racineURL' => RACINE_URL,
                'article' => $article,
                'categories' => $categories,
                'users' => $users,
                'session' => $_SESSION ?? [],
                ]);

        }
        break;


    // par défaut, on affiche la liste des articles
    default:
        // TODO : créer la vue et la logique pour lister les articles
        echo "<h1>Liste des articles (à faire)</h1>";
        break;
}