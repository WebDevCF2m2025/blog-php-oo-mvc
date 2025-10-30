<?php

namespace model\manager;

use PDO;
use Exception;
use model\mapping\UserMapping;
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

    // destruction de la session
    public function disconnect(): bool
    {
        // destruction des variables de session
        session_unset();
        // destruction du cookie
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        // succès de la déconnexion
        if(session_destroy()){
            return true;
        }else{
            return false;
                }
        
            }
        
            // récupération d'un utilisateur par son login
            public function getUserByLogin(string $login): ?UserMapping
            {
                // préparation de la requête
                $sql = "SELECT u.* , r.`role_name`
                        FROM `user` u
                            INNER JOIN `role` r
                            ON u.`user_role_id` = r.`role_id`
                        WHERE u.`user_login` = ? AND u.`user_activate` = 1 ; ";
                $stmt = $this->connect->prepare($sql);
                try {
                    $stmt->execute([$login]);
                    if ($stmt->rowCount() != 1)
                        return null;
                    $result = $stmt->fetch(PDO::FETCH_ASSOC);
                    $stmt->closeCursor();
                    // on efface les champs non désirés
                    unset($result['user_pwd'], $result['user_activate'], $result['user_hidden_id']);
                    // on retourne un objet UserMapping
                    return new UserMapping($result);
                } catch (Exception $e) {
                    echo $e->getMessage();
                    return null;
                }
            }

            // récupération de tous les utilisateurs
            public function getAllUsers(): array
            {
            $sql = "SELECT u.user_id, u.user_login, u.user_real_name FROM `user` u WHERE u.`user_activate` = 1 ORDER BY u.`user_login`";
            $stmt = $this->connect->prepare($sql);
            try {
                $stmt->execute();
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $stmt->closeCursor();
                $listUsers = [];
                foreach ($result as $user) {
                    $listUsers[] = new UserMapping($user);
                    }
                return $listUsers;
                } catch (Exception $e) {
                    echo $e->getMessage();
                    return [];
                }
            }

            public function register(array $data): bool
            {
                // check if passwords match
                if ($data['user_pwd'] !== $data['user_pwd_confirm']) {
                    throw new Exception("Les mots de passe ne correspondent pas.");
                }

                // check if user exists
                if ($this->userExists($data['user_login'], $data['user_mail'])) {
                    throw new Exception("Ce login ou cet email est déjà utilisé.");
                }

                // create a new user mapping
                $user = new UserMapping($data);

                // generate hidden id
                $user->setUserHiddenId($this->generateHiddenId());

                // hash password
                $user->setUserPwd(password_hash($data['user_pwd'], PASSWORD_DEFAULT));

                // set role to user
                $user->setUserRoleId(3);

                // set user to inactive
                $user->setUserActivate(0);

                // prepare request
                $sql = "INSERT INTO `user` (`user_login`, `user_pwd`, `user_mail`, `user_real_name`, `user_hidden_id`, `user_role_id`, `user_activate`) VALUES (?, ?, ?, ?, ?, ?, ?)";
                $stmt = $this->connect->prepare($sql);

                try {
                    $stmt->execute([
                        $user->getUserLogin(),
                        $user->getUserPwd(),
                        $user->getUserMail(),
                        $user->getUserRealName(),
                        $user->getUserHiddenId(),
                        $user->getUserRoleId(),
                        $user->getUserActivate(),
                    ]);
                    return true;
                } catch (Exception $e) {
                    echo $e->getMessage();
                    return false;
                }
            }

            public function userExists(string $login, string $email): bool
            {
                $sql = "SELECT `user_id` FROM `user` WHERE `user_login` = ? OR `user_mail` = ?";
                $stmt = $this->connect->prepare($sql);
                try {
                    $stmt->execute([$login, $email]);
                    return $stmt->rowCount() > 0;
                } catch (Exception $e) {
                    echo $e->getMessage();
                    return false;
                }
            }

            function generateHiddenId(): string
            {
                return uniqid('user_', true);
            }
        }
        