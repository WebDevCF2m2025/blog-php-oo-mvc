<?php
// création du namespace
namespace model;

interface UserInterface
{
    public function encodePwd(string $pwd):bool|string;
    public function validatePwd(string $pwd):bool;

}