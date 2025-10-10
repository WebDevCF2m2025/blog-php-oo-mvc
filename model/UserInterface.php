<?php

interface UserInterface
{
    public function encodePwd(string $pwd):bool|string;
    public function validatePwd(string $pwd):bool;

}