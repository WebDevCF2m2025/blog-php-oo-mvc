<?php

namespace model\mapping;

use model\AbstractMapping;

class CategoryMapping extends AbstractMapping
{
    protected ?int $category_id=null;
    protected ?string $category_title=null;
    protected ?string $category_slug=null;
    protected ?string $category_description=null;
    protected ?int $category_parent=null;

    public function getCategoryId(): ?int
    {
        return $this->category_id;
    }

    public function setCategoryID(?int $category_id): void
    {
        $this->category_id = $category_id;

    }

    public function getCategoryTitle(): ?string
    {
        return $this->category_title;
    }

    public function setCategoryTitle(?string $category_title): void
    {
        $this->category_title = $category_title;
    }

    public function getCategorySlug(): ?string
    {
        return $this->category_slug;
    }

    public function setCategorySlug(?string $category_slug): void
    {
        $this->category_slug = $category_slug;
    }

    public function getCategoryDescription(): ?string
    {
        return $this->category_description;
    }

    public function setCategoryDescription(?string $category_description): void
    {
        $this->category_description = $category_description;
    }

    public function getCategoryParent(): ?int
    {
        return $this->category_parent;
    }

    public function setCategoryParent(?int $category_parent): void
    {
        $this->category_parent = $category_parent;
    }


}