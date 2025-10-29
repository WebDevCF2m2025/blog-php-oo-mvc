<?php
// path: controller/adminController/commentController.php

// action par défaut
$action = $_GET['action'] ?? 'list';

// switch pour les actions
switch ($action) {
    // mise à jour de la visibilité
    case "update":
        // si l'id et le status sont présents
        if (isset($_GET['id']) && isset($_GET['status'])) {
            $id = (int)$_GET['id'];
            $status = (int)$_GET['status'];
            // mise à jour de la visibilité
            $commentManager->updateCommentVisibility($id, $status);
        }
        // redirection vers la liste
        header("Location: ?pg=admin&slug=commentaire");
        break;
    // liste des commentaires
    case "list":
    default:
        // récupération de tous les commentaires
        $comments = $commentManager->getAllComments();
        // affichage de la vue
        echo $twig->render('backend/list.comment.back.html.twig', [
            'racineURL' => RACINE_URL,
            'session' => $_SESSION ?? [],
            'comments' => $comments,
        ]);
        break;
}