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
        $this->comment_text = $comment_text;
    }

    public function getCommentCreate(): ?string
    {
        return $this->comment_create;
    }

    public function setCommentCreate(?string $comment_create): void
    {
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

    public function setCommentArticleId(?int $comment_article_id): void
    {
        $this->comment_article_id = $comment_article_id;
    }

    public function getCommentUserId(): ?int
    {
        return $this->comment_user_id;
    }

    public function setCommentUserId(?int $comment_user_id): void
    {
        $this->comment_user_id = $comment_user_id;
    }


}