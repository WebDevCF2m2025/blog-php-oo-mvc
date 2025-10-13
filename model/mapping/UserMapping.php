<?php

namespace model\mapping;


use model\AbstractMapping;

class UserMapping extends AbstractMapping
{
    protected ?int $user_id=null;
    protected ?string $user_login=null;
    protected ?string $user_pwd=null;
    protected ?string $user_mail=null;
    protected ?string $user_real_name=null;
    protected ?string $user_date_inscription=null;
    protected ?string $user_hidden_id=null;
    protected ?int $user_activate=null;
    protected ?int $user_role_id=null;
    protected ?array $roles=null;

    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    public function setUserId(?int $user_id): void
    {
        $this->user_id = $user_id;
    }

    public function getUserLogin(): ?string
    {
        return $this->user_login;
    }

    public function setUserLogin(?string $user_login): void
    {
        $this->user_login = $user_login;
    }

    public function getUserPwd(): ?string
    {
        return $this->user_pwd;
    }

    public function setUserPwd(?string $user_pwd): void
    {
        $this->user_pwd = $user_pwd;
    }

    public function getUserMail(): ?string
    {
        return $this->user_mail;
    }

    public function setUserMail(?string $user_mail): void
    {
        $this->user_mail = $user_mail;
    }

    public function getUserRealName(): ?string
    {
        return $this->user_real_name;
    }

    public function setUserRealName(?string $user_real_name): void
    {
        $this->user_real_name = $user_real_name;
    }

    public function getUserDateInscription(): ?string
    {
        return $this->user_date_inscription;
    }

    public function setUserDateInscription(?string $user_date_inscription): void
    {
        $this->user_date_inscription = $user_date_inscription;
    }

    public function getUserHiddenId(): ?string
    {
        return $this->user_hidden_id;
    }

    public function setUserHiddenId(?string $user_hidden_id): void
    {
        $this->user_hidden_id = $user_hidden_id;
    }

    public function getUserActivate(): ?int
    {
        return $this->user_activate;
    }

    public function setUserActivate(?int $user_activate): void
    {
        $this->user_activate = $user_activate;
    }

    public function getUserRoleId(): ?int
    {
        return $this->user_role_id;
    }

    public function setUserRoleId(?int $user_role_id): void
    {
        $this->user_role_id = $user_role_id;
    }

    public function getRoles(): ?array
    {
        return $this->roles;
    }

    public function setRoles(?array $roles): void
    {
        $this->roles = $roles;
    }




}