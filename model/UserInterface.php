<?php
// création du namespace
namespace model;

interface UserInterface
{
    function encodePwd(string $pwd):bool|string;
    function validatePwd(string $pwd):bool;
    function connect(array $tab):bool;
    function disconnect():bool;
    function generateHiddenId():string;


}