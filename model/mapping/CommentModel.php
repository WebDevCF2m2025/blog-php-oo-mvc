<?php

namespace model\mapping;

use model\AbstractMapping;

class CommentModel extends AbstractMapping
{

    protected ?int $comment_id = null;
    protected ?string $comment_text = null;
    protected ?string $comment_create = null;
    protected ?int $comment_parent = null;
    protected ?int $comment_visibility = null;
    protected ?int $comment_article_id = null;
    protected ?int $comment_user_id = null;

}