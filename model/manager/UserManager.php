<?php

namespace model\manager;

use PDO;
use Exception;
use model\Mapping\UserMapping;
use model\ManagerInterface;
use model\UserInterface;

class UserManager implements ManagerInterface, UserInterface
{
    protected PDO $connect;


    public function __construct(PDO $connect)
    {
        $this->connect = $connect;
    }

    public function encodePwd(string $pwd): bool|string
    {
        // TODO: Implement encodePwd() method.
    }

    public function validatePwd(string $pwd): bool
    {
        // TODO: Implement validatePwd() method.
    }

    public function connect(array $tab): bool
    {
        // TODO: Implement connect() method.
    }

    public function disconnect(): bool
    {
        // TODO: Implement disconnect() method.
    }
}