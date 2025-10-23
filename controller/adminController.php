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


echo $twig->render('backend/homepage.back.html.twig', [
    // racine URL pour les liens
    'racineURL' => RACINE_URL,
    // la session pour savoir si l'utilisateur est connecté
    'session' => $_SESSION ?? [],
]);