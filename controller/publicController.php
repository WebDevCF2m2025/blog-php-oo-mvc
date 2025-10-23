<?php
// path : controller/publicController.php

// chemin vers les dépendances
use model\manager\CategoryManager;
use model\manager\ArticleManager;
use model\manager\UserManager;
use model\manager\CommentManager;
use model\mapping\CommentMapping;

// création des managers utiles
$categoryManager = new CategoryManager($connectPDO);
$articleManager = new ArticleManager($connectPDO);
$userManager = new UserManager($connectPDO);
$commentManager = new CommentManager($connectPDO);


// récupération des catégories pour le menu public
$categoriesMenu = $categoryManager->getCategoriesPublicMenu();

// homepage
if(empty($_GET['pg'])){

    // récupération des articles pour la homepage
    $articles = $articleManager->getArticlesHomepage();

    // appel de la vue
    // require_once RACINE_PATH."/view/home.html.php";
    echo $twig->render('homepage.html.twig',
    [
        // racine URL pour les liens
        'racineURL' => RACINE_URL,
        // mes catégories pour le menu
        'categories' => $categoriesMenu,
        // mes articles pour la homepage
        'articles'=> $articles,
        // la session pour savoir si l'utilisateur est connecté
        'session' => $_SESSION ?? [],
    ]);
// autres pages
}else{
    // récupération de la page demandée via $_GET['pg']
    $page = trim($_GET['pg']);

    // gestion des pages
    switch ($page) {

        // pages de type catégorie
        case "category":

            if(isset($_GET['slug'])) {
                // récupération d'une catégorie via son slug
                $category = $categoryManager->getCategoryBySlug($_GET['slug']);
                //var_dump($category);
                if($category!==null) {
                    // récupération des articles d'une catégorie
                    $articles = $articleManager->getArticlesByCategoryId($category->getCategoryId());
                    // appel de la vue
                    echo  $twig->render("category.html.twig",[
                        // racine URL pour les liens
                        'racineURL' => RACINE_URL,
                        // mes catégories pour le menu
                        'categories' => $categoriesMenu,
                        // la catégorie
                        'category' => $category,
                        // mes articles
                        'articles' => $articles,
                        // la session pour savoir si l'utilisateur est connecté
                        'session' => $_SESSION ?? [],
                    ]);
                }else{

                    // message d'erreur
                    $error = "Catégorie introuvable";
                    // appel de la vue 404
                    echo $twig->render("error.404.html.twig",['racineURL' => RACINE_URL,'categories' => $categoriesMenu,'session' => $_SESSION ?? [],'error' => $error]);
                }
            }else{
                // message d'erreur
                $error = "Slug manquant";
                // appel de la vue 404
                echo $twig->render("error.404.html.twig",['racineURL' => RACINE_URL,'categories' => $categoriesMenu,'session' => $_SESSION ?? [],'error' => $error]);
            }
            break;

        // page de type article
        case "article":

            if(isset($_GET['slug'])) {
                // récupération d'un article via son slug
                $article = $articleManager->getArticleBySlug($_GET['slug']);
                if($article!==null) {
                    // Récupération des commentaires pour cet article
                    $comments = $commentManager->getAllCommentsByArticleId($article->getArticleId());
                    // Ajout des commentaires à l'article
                    $article->setComments($comments);
                    // appel de la vue
                    echo $twig->render('article.html.twig',
                        [
                            // racine URL pour les liens
                            'racineURL' => RACINE_URL,
                            // mes catégories pour le menu
                            'categories' => $categoriesMenu,
                            // mon article
                            'article' => $article,
                            // la session pour savoir si l'utilisateur est connecté
                            'session' => $_SESSION ?? [],
                        ]);
                }else{
                    // message d'erreur
                    $error= "Article introuvable";
                    // appel de la vue 404
                    echo $twig->render("error.404.html.twig",['racineURL' => RACINE_URL,'categories' => $categoriesMenu,'session' => $_SESSION ?? [],'error' => $error]);
                }
            }else{
                // message d'erreur
                $error ="Slug manquant";
                // appel de la vue 404
                echo $twig->render("error.404.html.twig",['racineURL' => RACINE_URL,'categories' => $categoriesMenu,'session' => $_SESSION ?? [],'error' => $error]);
            }

            break;
        case "user":
            // page utilisateur
            echo "<h2>Nous serons sur la page d'un auteur</h2>";
            var_dump($_GET);
            break;
        // on veut se connecter
        case "connection":
            // si on est déjà connecté
            if(isset($_SESSION['user_id'])){
                // redirection vers la page d'accueil
                header("Location: ".RACINE_URL);
                exit();
            }
            // message d'erreur
            $error = "";
            // si on tente de se connecter
            if(isset($_POST['user_login'],$_POST['user_pwd'])){
                // on tente de se connecter
                try {
                    $connection = $userManager->connect($_POST);
                    // si on est connecté
                    if($connection===true){
                        // redirection vers la page d'accueil
                        header("Location: ".RACINE_URL);
                        exit();
                    }else{
                        // sinon création d'un message d'erreur
                        $error = "Login et ou mot de passe non valide !";
                    }
                }catch (Exception $e){
                    // sinon on récupère le message d'erreur
                    $error = $e->getMessage();
                }
            }

            // vue Twig de la page de connexion
            echo $twig->render('connection.html.twig',
                [
                    // racine URL pour les liens
                    'racineURL' => RACINE_URL,
                    // mes catégories pour le menu
                    'categories' => $categoriesMenu,
                    // message d'erreur
                    'error' => $error,
                ]);
            break;

        // on veut se déconnecter
        case "disconnection":
            $disconnection = $userManager->disconnect();
            if($disconnection===true){
                // redirection vers la page d'accueil
                header("Location: ".RACINE_URL);
                exit();
            }
            break;

        default:
            // page 404
            $error = "Page non trouvée";
            // appel de la vue 404
            echo $twig->render("error.404.html.twig",['racineURL' => RACINE_URL,'categories' => $categoriesMenu,'session' => $_SESSION ?? [],'error' => $error]);
            break;
    }

}