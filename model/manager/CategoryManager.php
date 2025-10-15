<?php

namespace model\manager;

use model\ManagerInterface;
use PDO;
use model\mapping\CategoryMapping;

class CategoryManager implements ManagerInterface
{

    private PDO $db;
    public function __construct(PDO $connect)
    {
        $this->db = $connect;
    }

    // Récupération de toutes les catégories, pour le menu public
    public function getCategoriesPublicMenu(): array
    {
        $sql = "SELECT c.`category_id`, c.`category_title`, c.`category_slug`, c.`category_parent`
                FROM `category` c 
                ORDER BY c.`category_title` ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        // transformation des catégories en objets CategoryMapping
        $listCategories = [];
        foreach($categories as $category){
            $cat = new CategoryMapping($category);
            $listCategories[] = $cat;
        }
        return $listCategories;
    }
}