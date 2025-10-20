<?php

use model\manager\CategoryManager;
use model\manager\ArticleManager;
use model\Manager\UserManager;

// création des managers utiles
$categoryManager = new CategoryManager($connectPDO);
$articleManager = new ArticleManager($connectPDO);
$userManager = new UserManager($connectPDO);


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
    ]);
    /*
    // exemple d'utilisation basique de twig
    echo $twig->render('index.html.twig', [
        'name' => 'Michaël',
        'articles'=> $articles]);
    */
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
        // on veut se connecter
        case "connection":
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
                        $error = "Login et ou mot de passe non valide !";
                    }
                }catch (Exception $e){
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
        default:
            // page 404
            echo "<h1>404 - Page non trouvée</h1>";
            var_dump($_GET);
            break;
    }

}