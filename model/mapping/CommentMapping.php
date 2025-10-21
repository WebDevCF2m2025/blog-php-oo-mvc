<?php

namespace model\mapping;

use model\AbstractMapping;
use Exception;



class CommentMapping extends AbstractMapping
{

    protected ?int $comment_id = null;
    protected ?string $comment_text = null;
    protected ?string $comment_create = null;
    protected ?int $comment_parent = null;
    protected ?int $comment_visibility = null;
    protected ?int $comment_article_id = null;
    protected ?int $comment_user_id = null;
    protected ?UserMapping $user = null;

    public function getCommentId(): ?int
    {
        return $this->comment_id;
    }

    public function setCommentId(?int $comment_id): void
    {
        $this->comment_id = $comment_id;
    }

    public function getCommentText(): ?string
    {
        return $this->comment_text;
    }

    public function setCommentText(?string $comment_text): void
    {
        $comment_text = htmlspecialchars(strip_tags(trim($comment_text)));
        if(empty($comment_text))
            throw new Exception("Texte non valide");
        if(strlen($comment_text)<3 || strlen($comment_text)>600)
            throw new Exception("Le texte du commentaire doit faire entre 3 et 600 caractères");
        $this->comment_text = $comment_text;
    }

    public function getCommentCreate(): ?string
    {
        return $this->comment_create;
    }

    public function setCommentCreate(?string $comment_create): void
    {
        if(is_null($comment_create)) return;
        $comment_create = date('Y-m-d H:i:s', strtotime($comment_create));
        $this->comment_create = $comment_create;
    }

    public function getCommentParent(): ?int
    {
        return $this->comment_parent;
    }

    public function setCommentParent(?int $comment_parent): void
    {
        $this->comment_parent = $comment_parent;
    }

    public function getCommentVisibility(): ?int
    {
        return $this->comment_visibility;
    }

    public function setCommentVisibility(?int $comment_visibility): void
    {
        $this->comment_visibility = $comment_visibility;
    }

    public function getCommentArticleId(): ?int
    {
        return $this->comment_article_id;
    }

    public function setCommentArticleId(int $comment_article_id): void
    {
        if($comment_article_id<=0) throw new Exception("article_id doit être un entier positif");
        $this->comment_article_id = $comment_article_id;
    }

    public function getCommentUserId(): ?int
    {
        return $this->comment_user_id;
    }

    public function setCommentUserId(int $comment_user_id): void
    {
        if($comment_user_id<=0) throw new Exception("user_id doit être un entier positif");
        $this->comment_user_id = $comment_user_id;
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
