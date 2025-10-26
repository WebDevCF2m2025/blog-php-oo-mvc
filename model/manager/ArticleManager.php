<?php

namespace model\manager;

use model\ManagerInterface;
use model\mapping\ArticleMapping;
use model\mapping\CategoryMapping;
use model\mapping\UserMapping;
use model\StringTrait;
use PDO;
use Exception;

class ArticleManager implements ManagerInterface
{

    private PDO $db;

    public function __construct(PDO $connect)
    {
        $this->db = $connect;
    }

    // Récupération des Traits
    use StringTrait;

    // Articles visibles (article_visibility = 2), pour la homepage
    public function getArticlesHomepage(): array
    {
        $sql = "SELECT 
    a.`article_id`, a.`article_title`, a.`article_slug`, LEFT(a.`article_text`,150) AS article_text,  a.`article_date_publish`,a.`article_user_id`,
    
                       u.`user_id`, u.`user_login`, u.`user_real_name`,
                       (SELECT COUNT(comment.`comment_id`) FROM `comment` WHERE comment.`comment_article_id` = a.`article_id` AND comment.`comment_visibility` = 1) AS comment_count,
    
                       GROUP_CONCAT(c.`category_slug` SEPARATOR '|||') AS category_slug, 
                       GROUP_CONCAT(c.`category_title` SEPARATOR '|||') AS category_title
                FROM `article` a 
                INNER JOIN `user` u ON a.`article_user_id`=u.`user_id`
                LEFT JOIN `article_has_category` h on a.article_id = h.article_article_id    
                LEFT JOIN `category` c ON h.`category_category_id`= c.`category_id`
                WHERE a.article_visibility = 2
                GROUP BY a.`article_id`
                ORDER BY a.`article_date_publish` DESC";
        $stmt = $this->db->prepare($sql);
        try {
            $stmt->execute();
            $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt->closeCursor();
            $listArticles = [];
            foreach ($articles as $article) {
                // création d'un article
                $art = new ArticleMapping($article);
                // on coupe le texte de l'article à 140 caractères sans couper les mots
                // et on ajoute des points de suspension
                $art->setArticleText($this->cutTheText($art->getArticleText(), 140));
                // gestion de l'auteur de l'article
                $user = new UserMapping($article);
                $art->setUser($user);
                // gestion du nombre de commentaires de l'article
                $art->setComments(['comment_count' => $article['comment_count']]);
                // gestion des catégories de l'article
                $cats = [];
                if (isset($article['category_slug'])) {
                    $arrSlug = explode("|||", $article['category_slug']);
                    $arrTitle = explode("|||", $article['category_title']);
                    for ($i = 0; $i < count($arrSlug); $i++) {
                        $c = new CategoryMapping([]);
                        $c->setCategorySlug($arrSlug[$i]);
                        $c->setCategoryTitle($arrTitle[$i]);
                        $cats[] = $c;
                    }
                    $art->setCategories($cats);
                }
                $listArticles[] = $art;
            }
            return $listArticles;
        } catch (Exception $e) {
            echo "Erreur lors de la récupération des articles pour la homepage : " . $e->getMessage();
            return [];
        }
    }

    // tous les articles pour l'administration
    public function getAllArticles(): ?array
    {
        $sql = "SELECT 
                    -- articles
                    a.`article_id`, a.`article_title`, a.`article_slug`, LEFT(a.`article_text`,150) AS article_text,  a.`article_date_publish`, a.`article_date_create`,a.`article_visibility`,
                    -- user
                        u.`user_login`,
                    -- comment
                       (SELECT COUNT(co.`comment_id`) FROM `comment` co WHERE co.`comment_article_id` = a.`article_id`) AS comment_count,
                    -- category
                       (SELECT COUNT(ha.article_article_id) FROM `article_has_category` ha WHERE ha.article_article_id = a.`article_id`) AS category_count 

                FROM `article` a 
                INNER JOIN `user` u ON a.`article_user_id`=u.`user_id`
                ORDER BY a.`article_date_publish` DESC;";
        $stmt = $this->db->prepare($sql);
        try {
            $stmt->execute();
            $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt->closeCursor();
            $listArticles = [];
            foreach ($articles as $article) {
                // création d'un article
                $art = new ArticleMapping($article);
                // on coupe le texte de l'article à 140 caractères sans couper les mots
                // et on ajoute des points de suspension
                $art->setArticleText($this->cutTheText($art->getArticleText(), 140));
                // utilisateur
                $user = new UserMapping($article);
                $art->setUser($user);
                // gestion du nombre de commentaires de l'article
                $art->setComments(['comment_count' => $article['comment_count']]);
                // gestion du nombre de catégories de l'article
                $art->setCategories(['category_count' => $article['category_count']]);
                $listArticles[] = $art;
            }
            return $listArticles;

        }catch (Exception $e){
            echo "Erreur lors de la récupération des articles : " . $e->getMessage();
            return [];
        }
    }



    // Nous recherchons un article via son slug
    public function getArticleBySlug(string $slug): ?ArticleMapping
    {

        $sql = "SELECT a.*,
                       u.`user_id`, u.`user_login`, u.`user_real_name`,
                       GROUP_CONCAT(c.`category_slug` SEPARATOR '|||') AS category_slug, 
                       GROUP_CONCAT(c.`category_title` SEPARATOR '|||') AS category_title
                FROM `article` a 
                INNER JOIN `user` u ON a.`article_user_id`=u.`user_id`
                LEFT JOIN `article_has_category` h on a.article_id = h.article_article_id    
                LEFT JOIN `category` c ON h.`category_category_id`= c.`category_id`
                WHERE a.article_visibility = 2 AND a.article_slug = ?
                GROUP BY a.`article_id`
                ORDER BY a.`article_date_publish` DESC";
        $stmt = $this->db->prepare($sql);
        try {
            $stmt->execute([$slug]);
            $article = $stmt->fetch(PDO::FETCH_ASSOC);
            $stmt->closeCursor();
            if (empty($article))
                return null;
            // création d'un article
            $art = new ArticleMapping($article);
            // gestion de l'auteur de l'article
            $user = new UserMapping($article);
            $art->setUser($user);
            // gestion des catégories de l'article
            $cats = [];
            if (isset($article['category_slug'])) {
                $arrSlug = explode("|||", $article['category_slug']);
                $arrTitle = explode("|||", $article['category_title']);
                for ($i = 0; $i < count($arrSlug); $i++) {
                    $c = new CategoryMapping([]);
                    $c->setCategorySlug($arrSlug[$i]);
                    $c->setCategoryTitle($arrTitle[$i]);
                    $cats[] = $c;
                }
                $art->setCategories($cats);
            }
            return $art;
        } catch (Exception $e) {
            echo "Erreur lors de la récupération de l'article par son slug : " . $e->getMessage();
            return null;
        }
    }

    // récupération des articles par l'id de la catégorie
    public function getArticlesByCategoryId(int $id): ?array
    {
        $sql = "SELECT 
                -- article
                a.`article_id`, a.`article_title`, a.`article_slug`, LEFT(a.`article_text`,150) AS article_text,  a.`article_date_publish`,a.`article_user_id`,
                -- user
                u.`user_id`, u.`user_login`, u.`user_real_name`,
                -- comment
                (SELECT COUNT(co.`comment_id`) FROM `comment` co WHERE co.`comment_article_id` = a.`article_id` AND co.`comment_visibility` = 1
                ) AS comment_count,
                -- category
                (SELECT GROUP_CONCAT(c.`category_slug`, '|||', c.`category_title` SEPARATOR '----')  FROM category c 
                        INNER JOIN `article_has_category` ahc 
                            ON ahc.`category_category_id` = c.`category_id`
                        WHERE ahc.`article_article_id` = a.`article_id`
                GROUP BY a.`article_id`                                           
                ) as acategories
                FROM `article` a 
                    INNER JOIN `user` u ON a.`article_user_id`=u.`user_id`
                    INNER JOIN `article_has_category` h on a.article_id = h.article_article_id    
                WHERE a.article_visibility = 2 AND h.`category_category_id` = ?
                ORDER BY a.`article_date_publish` DESC;
    ";
        $stmt = $this->db->prepare($sql);
        try {
            $stmt->execute([$id]);
            // si pas d'articles
            if($stmt->rowCount()==0)
                return null;
            $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt->closeCursor();
            $listArticles = [];
            foreach ($articles as $article) {
                // création d'un article
                $art = new ArticleMapping($article);
                // on coupe le texte de l'article à 140 caractères sans couper les mots
                // et on ajoute des points de suspension
                $art->setArticleText($this->cutTheText($art->getArticleText(), 140));
                // gestion de l'auteur de l'article
                $user = new UserMapping($article);
                $art->setUser($user);
                // gestion du nombre de commentaires de l'article
                $art->setComments(['comment_count' => $article['comment_count']]);
                // gestion des catégories de l'article
                $cats = [];
                if (isset($article['acategories'])) {
                    $arr = explode("----", $article['acategories']);
                    foreach ($arr as $cat) {
                        $arrCat = explode("|||", $cat);
                        $c = new CategoryMapping([]);
                        $c->setCategorySlug($arrCat[0]);
                        $c->setCategoryTitle($arrCat[1]);
                        $cats[] = $c;
                    }
                    $art->setCategories($cats);
                }
                $listArticles[] = $art;
            }
            return $listArticles;

        } catch (Exception $e) {
            echo "Erreur lors de la récupération des articles par catégorie : " . $e->getMessage();
            return [];
        }
    }

    // récupération des articles par l'id de l'utilisateur
    public function getArticlesByUserId(int $id): ?array
    {
        $sql = "SELECT 
                -- article
                a.`article_id`, a.`article_title`, a.`article_slug`, LEFT(a.`article_text`,150) AS article_text,  a.`article_date_publish`,a.`article_user_id`,
                -- user
                u.`user_id`, u.`user_login`, u.`user_real_name`,
                -- comment
                (SELECT COUNT(comment.`comment_id`) FROM `comment` WHERE comment.`comment_article_id` = a.`article_id` AND comment.`comment_visibility` = 1
                ) AS comment_count,
                -- category
                (SELECT GROUP_CONCAT(c.`category_slug`, '|||', c.`category_title` SEPARATOR '----')  FROM category c 
                        INNER JOIN `article_has_category` ahc 
                            ON ahc.`category_category_id` = c.`category_id`
                        WHERE ahc.`article_article_id` = a.`article_id`
                GROUP BY a.`article_id`                                           
                ) as acategories
                FROM `article` a 
                    INNER JOIN `user` u ON a.`article_user_id`=u.`user_id`
                WHERE a.article_visibility = 2 AND a.`article_user_id` = ?
                ORDER BY a.`article_date_publish` DESC;
    ";
        $stmt = $this->db->prepare($sql);
        try {
            $stmt->execute([$id]);
            // si pas d'articles
            if($stmt->rowCount()==0)
                return null;
            $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt->closeCursor();
            $listArticles = [];
            // tant que l'on a des articles
            foreach ($articles as $article) {
                // création d'un article
                $art = new ArticleMapping($article);
                // on coupe le texte de l'article à 140 caractères sans couper les mots
                // et on ajoute des points de suspension
                $art->setArticleText($this->cutTheText($art->getArticleText(), 140));
                // gestion de l'auteur de l'article
                $user = new UserMapping($article);
                $art->setUser($user);
                // gestion du nombre de commentaires de l'article
                $art->setComments(['comment_count' => $article['comment_count']]);
                // gestion des catégories de l'article
                $cats = [];
                if (isset($article['acategories'])) {
                    $arr = explode("----", $article['acategories']);
                    foreach ($arr as $cat) {
                        $arrCat = explode("|||", $cat);
                        $c = new CategoryMapping([]);
                        $c->setCategorySlug($arrCat[0]);
                        $c->setCategoryTitle($arrCat[1]);
                        $cats[] = $c;
                    }
                    $art->setCategories($cats);
                }
                $listArticles[] = $art;
            }
            return $listArticles;

        } catch (Exception $e) {
            echo "Erreur lors de la récupération des articles par utilisateur : " . $e->getMessage();
            return [];
        }
    }

    // insertion d'un article
    public function createArticle(ArticleMapping $article): bool
    {
        // on va utiliser une transaction pour insérer l'article et ses catégories
        $this->db->beginTransaction();
        try {
            // on crée le slug de l'article
            $slug = $this->slugify($article->getArticleTitle());

            // on vérifie si l'article est publié ou non
            if ($article->getArticleVisibility() == 2) {
                // on ajoute la date du jour
                $article->setArticleDatePublish(date("Y-m-d H:i:s"));
            }


            // on prépare la requête d'insertion de l'article
            $sql = "INSERT INTO `article`(`article_title`, `article_slug`, `article_text`, `article_user_id`, `article_visibility`,`article_date_publish`) VALUES (?,?,?,?,?,?)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                html_entity_decode($article->getArticleTitle()),
                $slug,
                $article->getArticleText(),
                $article->getArticleUserId(),
                $article->getArticleVisibility(),
                $article->getArticleDatePublish()
            ]);
            // on récupère l'id de l'article inséré
            $articleId = $this->db->lastInsertId();

            // on prépare la requête d'insertion des catégories
            $sql = "INSERT INTO `article_has_category`(`article_article_id`, `category_category_id`) VALUES (?,?)";
            $stmt = $this->db->prepare($sql);
            // on boucle sur les catégories de l'article
            foreach ($article->getCategories() as $category) {
                $stmt->execute([
                    $articleId,
                    $category->getCategoryId()
                ]);
            }
            // on valide la transaction
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            // on annule la transaction
            $this->db->rollBack();
            echo "Erreur lors de l'insertion de l'article : " . $e->getMessage();
            return false;
        }
    }
}
