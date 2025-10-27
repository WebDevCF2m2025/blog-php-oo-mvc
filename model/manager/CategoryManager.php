<?php

namespace model\manager;

use model\ManagerInterface;
use PDO;
use Exception;
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
                FROM `category` c -- WHERE c.`category_parent` = 0
                 -- WHERE c.`category_id` = 100
                ORDER BY c.`category_title` ASC";
        $stmt = $this->db->prepare($sql);
        try {
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
        }catch (Exception $e){
            echo "Erreur lors de la récupération des catégories pour le menu public : " . $e->getMessage();
            return [];
        }

    }

    // Récupération d'une catégorie via son slug
    public function getCategoryBySlug(string $slug): ?CategoryMapping
    {
        $sql = "SELECT c.*
                FROM `category` c
                     WHERE c.`category_slug` = ?
                ;";
        $stmt = $this->db->prepare($sql);
        try {
            $stmt->execute([$slug]);
            $category = $stmt->fetch(PDO::FETCH_ASSOC);
            $stmt->closeCursor();
            if(empty($category))
                return null;
            $cat = new CategoryMapping($category);
            return $cat;
        }catch (Exception $e){
            echo "Erreur lors de la récupération de la catégorie par son slug : " . $e->getMessage();
            return null;
        }
    }

    // Récupération de toutes les catégories
    public function getAllCategories(): array
    {
        $sql = "SELECT c.`category_id`, c.`category_title`, c.`category_slug`
                FROM `category` c
                ORDER BY c.`category_title` ASC";
        $stmt = $this->db->prepare($sql);
        try {
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
        }catch (Exception $e){
            echo "Erreur lors de la récupération des catégories pour le menu public : " . $e->getMessage();
            return [];
        }

    }

    public function createCategory(CategoryMapping $category): bool
    {
        $sql = "INSERT INTO `category` (`category_title`, `category_slug`) VALUES (?,?)";
        $stmt = $this->db->prepare($sql);
        try {
            $stmt->execute([
                html_entity_decode($category->getCategoryTitle()),
                $this->slugify($category->getCategoryTitle())
            ]);
            return true;
        }catch (Exception $e){
            echo "Erreur lors de l'insertion de la catégorie : " . $e->getMessage();
            return false;
        }
    }

    public function updateCategory(CategoryMapping $category): bool
    {
        $sql = "UPDATE `category` SET `category_title`=?, `category_slug`=? WHERE `category_id`=?";
        $stmt = $this->db->prepare($sql);
        try {
            $stmt->execute([
                html_entity_decode($category->getCategoryTitle()),
                $this->slugify($category->getCategoryTitle()),
                $category->getCategoryId()
            ]);
            return true;
        }catch (Exception $e){
            echo "Erreur lors de la modification de la catégorie : " . $e->getMessage();
            return false;
        }
    }

    public function deleteCategory(int $id): bool
    {
        $sql = "DELETE FROM `category` WHERE `category_id`=?";
        $stmt = $this->db->prepare($sql);
        try {
            $stmt->execute([$id]);
            return true;
        }catch (Exception $e){
            echo "Erreur lors de la suppression de la catégorie : " . $e->getMessage();
            return false;
        }
    }

    public function getCategoryById(int $id): ?CategoryMapping
    {
        $sql = "SELECT c.`category_id`, c.`category_title`, c.`category_slug`
                FROM `category` c
                WHERE c.`category_id` = ?";
        $stmt = $this->db->prepare($sql);
        try {
            $stmt->execute([$id]);
            $category = $stmt->fetch(PDO::FETCH_ASSOC);
            $stmt->closeCursor();
            if(empty($category))
                return null;
            $cat = new CategoryMapping($category);
            return $cat;
        }catch (Exception $e){
            echo "Erreur lors de la récupération de la catégorie par son id : " . $e->getMessage();
            return null;
        }
    }

}