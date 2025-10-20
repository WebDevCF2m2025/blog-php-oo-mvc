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


    public function connect(array $tab): bool
    {
        // si on a pas ce qu'il faut pour se connecter
        if (!isset($tab['user_login'], $tab['user_pwd']))
            return false;

            // création d'un UserMapping
            // pour protéger via les setters
            $user = new UserMapping($tab);

            // préparation de la requête
            $sql = "SELECT u.* , r.`role_name`
                FROM `user` u
                    INNER JOIN `role` r
                    ON u.`user_role_id` = r.`role_id`
                WHERE u.`user_login` = ? AND u.`user_activate` = 1 ;
                ";
            $stmt = $this->connect->prepare($sql);
            try {
                // on cherche via le login
                $stmt->execute([
                    $user->getUserLogin(),
                ]);
                // si on trouve pas, on renvoie false
                if ($stmt->rowCount() != 1)
                    return false;

                // si l'utilisateur existe, on transforme le resultat en tableau
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                // on ferme le curseur (bonne pratique).
                $stmt->closeCursor();

                // vérification du mot de passe haché
                if (password_verify($user->getUserPwd(), $result['user_pwd'])) {
                    // on met à jour la session avec les résultats de la requête
                    $_SESSION = $result;
                    // on efface les variables de session inutiles
                    unset($_SESSION['user_pwd'], $_SESSION['user_activate'], $_SESSION['user_hidden_id']);
                    // succès de la connexion
                    return true;
                    // pas de correspondance
                } else {
                    return false;
                }


            } catch (Exception $e) {
                echo $e->getMessage();
                return false;
            }

    }

    public function disconnect(): bool
    {
        // TODO: Implement disconnect() method.
    }

    function generateHiddenId(): string
    {
        // TODO: Implement generateHiddenId() method.
    }
}