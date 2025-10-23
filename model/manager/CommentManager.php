<?php

namespace model\manager;

use model\ManagerInterface;
use model\StringTrait;
use model\mapping\ArticleMapping;
use model\mapping\CommentMapping;
use model\mapping\UserMapping;
use PDO;
use Exception;

class CommentManager implements ManagerInterface
{

    private PDO $db;

    public function __construct(PDO $connect)
    {
        $this->db = $connect;
    }
    // Récupération des Traits
    use StringTrait;

    // récupération de tous les commentaires validés pour un article via son id
    public function getAllCommentsByArticleId(int $articleId): array
    {
        $sql = "SELECT c.*, u.user_id, u.user_login, u.user_real_name 
                FROM `comment` c
                INNER JOIN `user` u ON c.comment_user_id = u.user_id
                WHERE c.comment_article_id = ? AND c.comment_visibility = 1
                ORDER BY c.comment_create ASC";
        $prepare = $this->db->prepare($sql);
        try {
            $prepare->execute([$articleId]);
            // récupération des résultats et transformation en tableau associatif
            $result = $prepare->fetchAll(PDO::FETCH_ASSOC);
            $prepare->closeCursor();

            // création d'un tableau de commentaires
            $comments = [];
            // pour chaque ligne de résultat
            foreach ($result as $row) {
                // on utilise les setters de sécurisation pour
                // Comment et User en instanciant les classes correspondantes
                $comment = new CommentMapping($row);
                $user = new UserMapping($row);
                // on ajoute l'utilisateur au commentaire
                $comment->setUser($user);
                // on ajoute le commentaire au tableau
                $comments[] = $comment;
            }
            // on retourne le tableau contenant
            // les commentaires
            return $comments;

        } catch (Exception $e) {
            echo "Erreur lors de la récupération des commentaires : " . $e->getMessage();
            return [];
        }
    }

    // insertion d'un commentaire
    public function insertComment(CommentMapping $comment): bool
    {
        // si l'utilisateur n'est pas connecté
        if(!isset($_SESSION['user_id']))
            throw new Exception("Vous devez être connecté pour poster un commentaire");

        $visibility = 0; // par défaut en attente de validation

        // si l'utilisateur est Admin ou Editor, on peut publier directement
        if(isset($_SESSION['role_name']) && in_array($_SESSION['role_name'],['Admin','Editor']))
            $visibility = 1; // publié directement

        // préparation de la requête
        $sql = "INSERT INTO `comment` (comment_text, comment_article_id, comment_user_id, comment_visibility) 
                VALUES (:text, :article_id, :user_id, :visibility)";
        $prepare = $this->db->prepare($sql);
        try {
            $prepare->bindValue(':text', $comment->getCommentText());
            $prepare->bindValue(':article_id', $comment->getCommentArticleId(), PDO::PARAM_INT);
            $prepare->bindValue(':user_id', $comment->getCommentUserId(), PDO::PARAM_INT);
            $prepare->bindValue(':visibility', $visibility, PDO::PARAM_INT);
            $prepare->execute();
            return true;
        } catch (Exception $e) {
            echo "Erreur lors de l'insertion du commentaire : " . $e->getMessage();
            return false;
        }
    }
}
