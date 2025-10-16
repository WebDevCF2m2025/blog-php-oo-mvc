<?php

namespace model\mapping;

use Exception;
use model\AbstractMapping;
use model\StringTrait;
use model\mapping\UserMapping;

class ArticleMapping extends AbstractMapping
{
    // champs du mapping
    protected ?int $article_id = null;
    protected ?string $article_title = null;
    protected ?string $article_slug = null;
    protected ?string $article_text = null;
    protected ?string $article_date_create = null;
    protected ?string $article_date_publish = null;
    protected ?int $article_visibility = null;
    protected ?int $article_user_id = null;
    // champs de jointures
    protected ?array $comments = null;
    protected ?array $categories = null;

    protected ?UserMapping $user = null;

    // utilisation du trait
    use StringTrait;

    // getters and setters
    public function getArticleId(): ?int
    {
        return $this->article_id;
    }

    public function setArticleId(?int $article_id): void
    {
        if($article_id<=0) throw new Exception("article_id doit être un entier positif");
        $this->article_id = $article_id;
    }

    public function getArticleTitle(): ?string
    {
        return $this->article_title;
    }

    public function setArticleTitle(?string $article_title): void
    {
        $article_title = htmlspecialchars(strip_tags(trim($article_title)));
        if(strlen($article_title)<3 || strlen($article_title)>120){
            throw new Exception("Le titre de l'article doit faire entre 3 et 120 caractères");
        }
        $this->article_title = $article_title;
    }
    public function getArticleSlug(): ?string
    {
        return $this->article_slug;
    }

    public function setArticleSlug(?string $article_slug): void
    {
        $article_slug = htmlspecialchars(strip_tags(trim($article_slug)));
        if(strlen($article_slug)<3 || strlen($article_slug)>124){
            throw new Exception("Le slug de l'article doit faire entre 3 et 120 caractères");
        }
        $this->article_slug = $article_slug;
    }
    public function getArticleText(): ?string
    {
        return html_entity_decode($this->article_text);
    }
    public function setArticleText(?string $article_text): void
    {
        $article_text = htmlspecialchars(strip_tags(trim($article_text)));
        if(strlen($article_text)<10){
            throw new Exception("Le texte de l'article doit faire au moins 10 caractères");
        }
        $this->article_text = $article_text;
    }
    public function getArticleDateCreate(): ?string
    {
        return $this->article_date_create;
    }
    public function setArticleDateCreate(?string $article_date_create): void
    {
        if(is_null($article_date_create)) return;
        $date = date('Y-m-d H:i:s', strtotime($article_date_create));
        if(!$date) throw new Exception("La date d'inscription n'est pas au bon format");
        $this->article_date_create = $date;
    }
    public function getArticleDatePublish(): ?string
    {
        return $this->article_date_publish;
    }
    public function setArticleDatePublish(?string $article_date_publish): void
    {
        if(is_null($article_date_publish)) return;
        $date = date('Y-m-d H:i:s', strtotime($article_date_publish));
        if(!$date) throw new Exception("La date de publication n'est pas au bon format");
        $this->article_date_publish = $date;
    }
    public function getArticleVisibility(): ?int
    {
        return $this->article_visibility;
    }
    public function setArticleVisibility(?int $article_visibility): void
    {
        if(is_null($article_visibility)) return;
        if(!in_array($article_visibility,[0,1,2,3])){
            throw new Exception("La visibilité de l'article doit être 0 à 3");
        }
        $this->article_visibility = $article_visibility;
    }

    public function getArticleUserId(): ?int
    {
        return $this->article_user_id;
    }
    public function setArticleUserId(?int $article_user_id): void
    {
        if($article_user_id<=0) throw new Exception("article_user_id doit être un entier positif");
        $this->article_user_id = $article_user_id;
    }
    public function getComments(): ?array
    {
        return $this->comments;
    }
    public function setComments(?array $comments): void
    {
        $this->comments = $comments;
    }
    public function getCategories(): ?array
    {
        return $this->categories;
    }
    public function setCategories(?array $categories): void
    {
        $this->categories = $categories;
    }
    public function getUser(): ?UserMapping
    {
        return $this->user;
    }
    public function setUser(?UserMapping $user): void
    {
        $this->user = $user;
    }


}