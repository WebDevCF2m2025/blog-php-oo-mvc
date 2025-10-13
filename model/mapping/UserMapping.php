<?php

namespace model\mapping;


use model\AbstractMapping;
use Exception;

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
        if($user_id<=0) throw new Exception("user_id doit être un entier positif");
        $this->user_id = $user_id;
    }

    public function getUserLogin(): ?string
    {
        return $this->user_login;
    }

    public function setUserLogin(?string $user_login): void
    {
        $user_login = strip_tags(trim($user_login));
        // Ne peut pas commencer par un chiffre, ni contenir des espaces ou des caractères spéciaux
        if(preg_match('/^[a-zA-Z][a-zA-Z0-9]{2,29}$/',$user_login)){
            $this->user_login = $user_login;
        }else{
            throw new Exception("Votre username doit faire de 3 à 30 caractères, commencer par une lettre et ne contenir que des lettres et des chiffres non accentués");
        }

    }

    public function getUserPwd(): ?string
    {
        return $this->user_pwd;
    }

    public function setUserPwd(?string $user_pwd): void
    {
        $user_pwd = trim($user_pwd);
        $this->user_pwd = $user_pwd;
    }

    public function getUserMail(): ?string
    {
        return $this->user_mail;
    }

    public function setUserMail(?string $user_mail): void
    {
        if(filter_var($user_mail,FILTER_VALIDATE_EMAIL)){
            $this->user_mail = $user_mail;
        }else{
            throw new Exception("Votre email n'est pas valide");
        }
    }

    public function getUserRealName(): ?string
    {
        return $this->user_real_name;
    }

    public function setUserRealName(?string $user_real_name): void
    {
        if(is_null($user_real_name)) return;
        if(empty($user_real_name)) return;
        $this->user_real_name = htmlspecialchars(strip_tags(trim($user_real_name)));
    }

    public function getUserDateInscription(): ?string
    {
        return $this->user_date_inscription;
    }

    public function setUserDateInscription(?string $user_date_inscription): void
    {
        if(is_null($user_date_inscription)) return;
        $date = date('Y-m-d H:i:s', strtotime($user_date_inscription));
        if(!$date) throw new Exception("La date d'inscription n'est pas au bon format");
        $this->user_date_inscription = $date;
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