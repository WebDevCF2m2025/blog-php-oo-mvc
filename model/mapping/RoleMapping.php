<?php

namespace model\mapping;

use model\AbstractMapping;

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
        $this->role_id = $role_id;
    }

    public function getRoleName(): ?string
    {
        return $this->role_name;
    }

    public function setRoleName(?string $role_name): void
    {
        $this->role_name = $role_name;
    }

    public function getRoleDescription(): ?string
    {
        return $this->role_description;
    }

    public function setRoleDescription(?string $role_description): void
    {
        $this->role_description = $role_description;
    }


}