<?php
// création du namespace
namespace model;

// PDO n'est plus accessible dans cet espace de nom, on l'importe
use PDO;

interface ManagerInterface
{
    public function __construct(PDO $connect);
}