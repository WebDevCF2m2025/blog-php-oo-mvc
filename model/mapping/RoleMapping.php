<?php

namespace model\mapping;

use model\AbstractMapping;
use Exception;


class RoleMapping extends AbstractMapping
{
    protected ?int $role_id = null;
    protected ?string  $role_name= null;
    protected ?string $role_description= null;

    public function getRoleId(): ?int
    {
        return $this->role_id;
    }

    public function setRoleId(?int $role_id): void
    {
        if(is_null($role_id)) return;
        $this->role_id = $role_id;
    }

    public function getRoleName(): ?string
    {
        return $this->role_name;
    }

    public function setRoleName(?string $role_name): void
    {
        $role_name = htmlspecialchars(strip_tags(trim($role_name)));
        if(empty($role_name))
            throw new Exception("Nom non valide");
        if(strlen($role_name)<3 || strlen($role_name)>45){
            throw new Exception("Le nom du rôle doit faire entre 3 et 45 caractères");
        }
        $this->role_name = $role_name;
    }

    public function getRoleDescription(): ?string
    {
        return $this->role_description;
    }

    public function setRoleDescription(?string $role_description): void
    {
        if(is_null($role_description)) return;
        $role_description = htmlspecialchars(strip_tags(trim($role_description)));
        if(strlen($role_description)>500)
            throw new Exception("La description du rôle doit faire moins de 500 caractères");

        $this->role_description = $role_description;
    }


}