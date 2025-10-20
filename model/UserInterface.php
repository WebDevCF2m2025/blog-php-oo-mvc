<?php
// création du namespace
namespace model;

interface UserInterface
{

    function connect(array $tab):bool;
    function disconnect():bool;
    function generateHiddenId():string;


}