<?php

namespace model\mapping;

use model\AbstractMapping;
use Exception;


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
        $category_title = htmlspecialchars(strip_tags(trim($category_title)));
        if(empty($category_title))
            throw new Exception("Nom non valide");
        if(strlen($category_title)<3 || strlen($category_title)>100)
            throw new Exception("Le nom de la catégorie doit faire entre 3 et 100 caractères");
        $this->category_title = $category_title;
    }

    public function getCategorySlug(): ?string
    {
        return $this->category_slug;
    }

    public function setCategorySlug(?string $category_slug): void
    {
        $category_slug = htmlspecialchars(strip_tags(trim($category_slug)));
        if(empty($category_slug))
            throw new Exception("Slug non valide");
        if(strlen($category_slug)<3 || strlen($category_slug)>104)
            throw new Exception("Le slug de la catégorie doit faire entre 3 et 104 caractères");
        $this->category_slug = $category_slug;
    }

    public function getCategoryDescription(): ?string
    {
        return html_entity_decode($this->category_description);
    }

    public function setCategoryDescription(?string $category_description): void
    {
        if(is_null($category_description)) return;
        $category_description = htmlspecialchars(strip_tags(trim($category_description)));
        if(strlen($category_description)>400)
            throw new Exception("La description de la catégorie doit faire moins de 400 caractères");
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