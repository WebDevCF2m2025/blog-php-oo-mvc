<?php

namespace model\manager;

use model\ManagerInterface;
use PDO;

class CategoryManager implements ManagerInterface
{

    private PDO $db;
    public function __construct(PDO $connect)
    {
        $this->db = $connect;
    }
    public function getCategories(): array
    {
        $sql = "SELECT c.`category_id`, c.`category_title`, c.`category_slug`, c.`category_parent`
                FROM `category` c 
                ORDER BY c.`category_title` ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}