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

    public function getAllCommentsByArticleId(int $articleId): array
    {
        $sql = "SELECT c.*, u.user_id, u.user_login , u.user_real_name
                FROM `comment` c
                INNER JOIN `user` u ON c.comment_user_id = u.user_id
                WHERE c.comment_article_id = :articleId AND c.comment_visibility = 1
                ORDER BY c.comment_create ASC";
        $prepare = $this->db->prepare($sql);
        try {
            $prepare->bindValue(':articleId', $articleId, PDO::PARAM_INT);
            $prepare->execute();
            $result = $prepare->fetchAll(PDO::FETCH_ASSOC);
            $prepare->closeCursor();

            $comments = [];
            foreach ($result as $row) {
                $comment = new CommentMapping($row);
                $user = new UserMapping($row);
                $comment->setUser($user);
                $comments[] = $comment;
            }
            return $comments;

        } catch (Exception $e) {
            echo "Erreur lors de la récupération des commentaires : " . $e->getMessage();
            return [];
        }
    }
}
