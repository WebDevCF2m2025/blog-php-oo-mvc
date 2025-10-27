<?php
// path: controller/adminController/categoryController.php

use model\manager\CategoryManager;
use model\mapping\CategoryMapping;

// on récupère l\'action
$action = $_GET['id'] ?? 'list';

// on instancie le manager
$categoryManager = new CategoryManager($connectPDO);

switch($action){
    // liste des catégories
    case 'list':
        // on récupère toutes les catégories
        $categories = $categoryManager->getAllCategories();
        // on affiche la vue
        echo $twig->render("backend/list.category.html.twig",[
            'racineURL' => RACINE_URL,
            'categories' => $categories,
            'session' => $_SESSION,
        ]);
    break;
    // ajout d\'une catégorie
    case 'add':
        // si on a soumis le formulaire
        if(!empty($_POST)){
            try{
                // on crée une nouvelle catégorie
                $category = new CategoryMapping($_POST);
                // on insère la catégorie
                $insert = $categoryManager->createCategory($category);
                if($insert){
                    // on redirige vers la liste des catégories
                    header("Location: " . RACINE_URL . "admin/categorie/?comment=success");
                    exit();
                }else{
                    $error = "Erreur lors de l\'insertion de la catégorie";
                }
            }catch(Exception $e){
                $error = $e->getMessage();
            }
        } 

        // on affiche la vue
        echo $twig->render('backend/add.category.back.html.twig', [
            'racineURL' => RACINE_URL,
            'session' => $_SESSION,
        ]);
        break;
    // suppresion d\'une catégorie
    case 'delete':
        if(isset($_GET['ident'])) {
            // on récupère l\'id de la catégorie
            $id = $_GET['ident'];
            // on supprime la catégorie
            $delete = $categoryManager->deleteCategory($id);
            if($delete){
                // on redirige vers la liste des catégories
                header("Location: " . RACINE_URL . "admin/categorie/?comment=success");
                exit();
            }else{
                // message d\'erreur
                $error = "Erreur lors de la suppression de la catégorie";
                echo $twig->render("backend/error.404.back.html.twig", [\'racineURL\' => RACINE_URL, \'session\' => $_SESSION ?? [], \'error\' => $error]);
            }

        }else{
            // message d\'erreur
            $error = "Erreur lors de la suppression de la catégorie";

            echo $twig->render("backend/error.404.back.html.twig", [\'racineURL\' => RACINE_URL, \'session\' => $_SESSION ?? [], \'error\' => $error]);
        }
        break;
        // modification d\'une catégorie
    case 'update':
        // si on a soumis le formulaire
        if(!empty($_POST)) {
            try{
                // on crée une nouvelle catégorie
                $category = new CategoryMapping($_POST);
                // on met à jour la catégorie
                $update = $categoryManager->updateCategory($category);
                if($update){
                    // on redirige vers la liste des catégories
                    header("Location: " . RACINE_URL . "admin/categorie/?comment=success");
                    exit();
                }else{
                    $error = "Erreur lors de la modification de la catégorie";
                }
            }catch(Exception $e){
                $error = $e->getMessage();
            }
        }else{
            // on récupère l\'id de la catégorie
            $id = $_GET['ident'];
            // on récupère la catégorie
            $category = $categoryManager->getCategoryById($id);

            // on récupère la vue
            echo $twig->render("backend/update.category.back.html.twig", [
                'racineURL' => RACINE_URL,
                'category' => $category,
                'session' => $_SESSION ?? [],
                ]);

        }
        break;


    // par défaut, on affiche la liste des articles
    default:
        // TODO : créer la vue et la logique pour lister les articles
        echo "<h1>Liste des catégories (à faire)</h1>";
        break;
}