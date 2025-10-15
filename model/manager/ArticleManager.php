<?php

namespace model\manager;

use model\ManagerInterface;
use PDO;

class ArticleManager implements ManagerInterface
{

    private PDO $db;
    public function __construct(PDO $connect)
    {
        $this->db = $connect;
    }
    public function getArticles(): array
    {
        $sql = "SELECT a.`article_id`, a.`article_title`, a.`article_slug`, a.`article_text`,  a.`article_date_publish`,a.`article_user_id`,
                       u.`user_id`, u.`user_login`, u.`user_real_name`,
                       GROUP_CONCAT(c.`category_slug` SEPARATOR '|||') AS category_slug, GROUP_CONCAT(c.`category_title` SEPARATOR '|||') AS category_title
                FROM `article` a 
                INNER JOIN `user` u ON a.`article_user_id`=u.`user_id`
                LEFT JOIN `article_has_category` h on a.article_id = h.article_article_id    
                LEFT JOIN `category` c ON h.`category_category_id`= c.`category_id`
                WHERE a.article_visibility = 2
                GROUP BY a.`article_id`
                ORDER BY a.`article_date_publish` DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}