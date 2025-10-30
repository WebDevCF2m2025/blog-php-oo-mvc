<?php
// path : controller/publicController.php

// chemin vers les dépendances
use model\manager\CategoryManager;
use model\manager\ArticleManager;
use model\manager\UserManager;
use model\manager\CommentManager;
use model\mapping\CommentMapping;
use Symfony\Component\Mime\Email;

// création des managers utiles
$categoryManager = new CategoryManager($connectPDO);
$articleManager = new ArticleManager($connectPDO);
$userManager = new UserManager($connectPDO);
$commentManager = new CommentManager($connectPDO);


// récupération des catégories pour le menu public
$categoriesMenu = $categoryManager->getCategoriesPublicMenu();

// on veut valider son compte
if(isset($_GET['validation'])){
    $validateUser = $userManager->validateUser($_GET['validation']);
    if($validateUser===true){
        header("Location: " . RACINE_URL . "connection/?");
    }
}

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
                            // on a posté un commentaire
                            'comment' => $_GET['comment'] ?? null,
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

        // on veut envoyer un commentaire
        case "comment":

            // on vérifie que l'utilisateur est connecté
            if(isset($_SESSION['user_id'])
            // et qu'il n'usurpe pas l'identité d'un autre utilisateur
            && $_SESSION['user_id']==$_POST['comment_user_id']
            // et qu'il poste un commentaire sur l'article affiché
            && $_POST['comment_article_id']==$_GET['id']
            ) {
                // tout est ok, on peut continuer

                // on hydrate le commentaire
                $newComment = new CommentMapping($_POST);
                try {
                    // insertion du commentaire en base de données
                    $insertComment = $commentManager->insertComment($newComment);

                    if ($insertComment === true) {
                        // redirection vers la page de l'article avec un message de succès
                        if(isset($_SESSION['role_name']) && ($_SESSION['role_name'] === 'Admin' || $_SESSION['role_name'] === 'Editor')){
                            header("Location: " . RACINE_URL . "article/" . $_GET['slug'] . "/?comment=success_auto_approved");
                            exit();
                        }else{
                            header("Location: " . RACINE_URL . "article/" . $_GET['slug'] . "/?comment=success_pending_approval");
                            exit();
                        }
                    } else {
                        // redirection vers la page de l'article avec un message d'erreur
                        header("Location: " . RACINE_URL . "article/" . $_GET['slug'] . "/?comment=error");
                        exit();
                    }
                }catch (Exception $e){
                    // redirection vers la page de l'article avec un message d'erreur
                    header("Location: ".RACINE_URL."article/".$_GET['slug']."/?comment=error");
                    exit();
                }

            }else{
                // redirection vers la page de l'article avec un message d'erreur
                header("Location: ".RACINE_URL."article/".$_GET['slug']."/?comment=error");
                exit();
            }
            
            break;

        // page utilisateur
        case "user":

            if(isset($_GET['slug'])) {
                // récupération d'un utilisateur via son slug
                $user = $userManager->getUserByLogin($_GET['slug']);
                if($user!==null) {
                    // récupération des articles d'un utilisateur
                    $articles = $articleManager->getArticlesByUserId($user->getUserId());
                    // appel de la vue
                    echo  $twig->render("user.html.twig",[
                        // racine URL pour les liens
                        'racineURL' => RACINE_URL,
                        // mes catégories pour le menu
                        'categories' => $categoriesMenu,
                        // l'utilisateur
                        'user' => $user,
                        // ses articles
                        'articles' => $articles,
                        // la session pour savoir si l'utilisateur est connecté
                        'session' => $_SESSION ?? [],
                    ]);
                }else{
                    // message d'erreur
                    $error = "Utilisateur introuvable";
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
                        // si on a une redirection
                        if(isset($_POST['redirect'])){
                            // redirection vers la page de l'article
                            header("Location: ".RACINE_URL."article/".$_POST['redirect']);
                            exit();
                        }
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
                    // redirection
                    'redirect' => $_GET['redirect'] ?? null,
                    // inscription réussie
                    'register' => $_GET['register'] ?? null,
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

        // on veut s'inscrire
        case "inscription":
            // message d'erreur
            $error = "";
            // si on tente de s'inscrire
            if(isset($_POST['user_login'], $_POST['user_mail'], $_POST['user_pwd'], $_POST['user_pwd_confirm'], $_POST['user_real_name'])){
                // on tente de s'inscrire
                try {
                    $inscription = $userManager->register($_POST);
                    // si on est inscrit
                    if(is_object($inscription)){
                        $userInscrit = $inscription;
                        $email = (new Email())
                            ->from('gitweb@cf2m.be')
                            ->to($userInscrit->getUserMail())
                            //->cc('cc@example.com')
                            //->bcc('bcc@example.com')
                            //->replyTo('fabien@example.com')
                            //->priority(Email::PRIORITY_HIGH)
                            ->subject('Validez votre inscription')
                            ->text('Bienvenue sur notre site, '.$userInscrit->getUserLogin().' Merci de valider votre inscription sur notre blog '.RACINE_URL.'?validation='.$userInscrit->getUserHiddenId().' avant le '.date("Y-m-d H:i:s",$userInscrit->getUserDateInscription())+(60*60*24))

                                    ->html('<h3>Bienvenue sur notre site, '.$userInscrit->getUserLogin().'</h3><p>Merci de valider votre inscription sur notre blog dans les 24 h</p><a href="'.RACINE_URL.'?validation='.$userInscrit->getUserHiddenId().'">valider</a>');

                        $mailer->send($email);
                        // redirection vers la page de connexion
                        header("Location: ".RACINE_URL."connection/?register=success");
                        exit();
                    }else{
                        // sinon création d'un message d'erreur
                        $error = "Erreur lors de l'inscription !";
                    }
                }catch (Exception $e){
                    // sinon on récupère le message d'erreur
                    $error = $e->getMessage();
                }
            }

            // vue Twig de la page d'inscription
            echo $twig->render('inscription.html.twig',
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
            $error = "Page non trouvée";
            // appel de la vue 404
            echo $twig->render("error.404.html.twig",['racineURL' => RACINE_URL,'categories' => $categoriesMenu,'session' => $_SESSION ?? [],'error' => $error]);
            break;
    }

}