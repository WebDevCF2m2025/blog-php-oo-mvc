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


// récupération de la page demandée via $_GET['pg']
$page = trim($_GET['pg']);

// gestion des pages
switch ($page) {
    // admin homepage
    case "admin":
        echo $twig->render('backend/homepage.back.html.twig', [
            // racine URL pour les liens
            'racineURL' => RACINE_URL,
            // la session pour savoir si l'utilisateur est connecté
            'session' => $_SESSION ?? [],
        ]);
        break;
    // Pages des listes
    # liste des articles
    case "articles":
        break;
    # liste des catégories
    case "categories":
        break;
    # liste des commentaires
    case "commentaires":
        break;
    # liste des utilisateurs
    case "users":
        break;
    default:

}
