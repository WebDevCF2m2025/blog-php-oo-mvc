<?php
// path : controller/adminController.php

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


// page d'accueil de l'administration
if (empty($_GET['slug'])) {

    // admin homepage
    echo $twig->render('backend/homepage.back.html.twig', [
        // racine URL pour les liens
        'racineURL' => RACINE_URL,
        // la session pour savoir si l'utilisateur est connecté
        'session' => $_SESSION ?? [],
    ]);

// autre page de l'administration
} else {

    // récupération de la page demandée via $_GET['slug']
    $page = trim($_GET['slug']);
    switch ($page) {

        # gestion des articles
        case "article":
            require_once RACINE_PATH . "/controller/adminController/articleController.php";
            break;
        # gestion des catégories
        case "categorie":
            require_once RACINE_PATH . "/controller/adminController/categoryController.php";
            break;
        # gestion des commentaires
        case "commentaire":
            require_once RACINE_PATH . "/controller/adminController/commentController.php";
            break;
        # gestion des utilisateurs
        case "user":
            require_once RACINE_PATH . "/controller/adminController/userController.php";
            break;
        default:
            // page 404
            $error = "Page non trouvée";
            // appel de la vue 404
            echo $twig->render("backend/error.404.back.html.twig", ['racineURL' => RACINE_URL, 'session' => $_SESSION ?? [], 'error' => $error]);

    }
}
