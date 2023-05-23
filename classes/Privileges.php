<?php

require_once './classes/Pdo.php';
require_once './classes/Db.php';
require_once './htmlpurifier-4.15.0/library/HTMLPurifier.auto.php';

class PrivilegeManager_
{
    private $db;
    private $purifier;
    public function __construct()
    {
        //$this->db = $db;
        $config = HTMLPurifier_Config::createDefault();
        $this->purifier = new HTMLPurifier($config);
        try {
            $this->db = new PDO('mysql:host=localhost;dbname=news', 'root', '');
        } catch (PDOException $e) {
            die();
        }
    }
    public function getAllRoles()
    {
        try {
            $sql = "SELECT * FROM roles";
            $result = $this->db->prepare($sql);
            $roles = array();
            while ($row = $result->fetch()) {
                $roles[$row['id']] = $row['name'];
            }
            return $roles;
        } catch (PDOException $e) {
            die();
        }
    }

    public function check_privileges($login)
    {
        $login = $this->purifier->purify($login);
        try {
            $sql = "SELECT p.id,p.name FROM privilege p"
                . " INNER JOIN user_privilege up ON p.id=up.id_privilege"
                . " INNER JOIN user u ON u.id=up.id_user"
                . " WHERE u.login=:login";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['login' => $login]);
            $data = $stmt->fetchAll();
            foreach ($data as $row) {
                $privilege = $row['name'];
                $_SESSION[$privilege] = 'YES';

            }

            $data['status'] = 'success';
            return $data;
        } catch (Exception $e) {
            print 'Exception' . $e->getMessage();
        }
        return [
            'status' => 'failed'
        ];
    }

    public function hasPrivilege($userId, $privilegeId)
    {
        // Sprawdź, czy użytkownik ma przypisaną daną uprawnienie
        $sql = "SELECT COUNT(*) FROM user_privilege WHERE id_user = :userId AND id_privilege = :privilegeId";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':privilegeId', $privilegeId, PDO::PARAM_INT);
        $stmt->execute();

        $count = $stmt->fetchColumn();
        return ($count > 0);
    }
    public function showMessages($userId)
    {
        if ($this->hasPrivilege($userId, 3)) {
            // Wykonaj operację wyświetlania wiadomości
            // ...
            echo "Wyświetlono wiadomości.";
        } else {
            echo "Brak uprawnień do wyświetlania wiadomości.";
        }
    }


    public function addMessage($userId, $message)
    {
        if ($this->hasPrivilege($userId, 1)) {
            // Wykonaj operację dodawania wiadomości
            // ...
            echo "Dodano wiadomość: " . $message;
        } else {
            echo "Brak uprawnień do dodawania wiadomości.";
        }
    }

    public function deleteMessage($userId, $messageId)
    {
        if ($this->hasPrivilege($userId, 2)) {
            // Wykonaj operację usuwania wiadomości
            // ...
            echo "Usunięto wiadomość o ID: " . $messageId;
        } else {
            echo "Brak uprawnień do usuwania wiadomości.";
        }
    }

    public function getUserRoleAndPrivileges($userId)
    {
        $sql = "SELECT role.id AS role_id, privilege.id AS privilege_id
                FROM user
                LEFT JOIN role ON user.role_id = role.id
                LEFT JOIN role_privilege ON role.id = role_privilege.role_id
                LEFT JOIN privilege ON role_privilege.privilege_id = privilege.id
                WHERE user.id = :userId";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $roleIds = array();
        $privilegeIds = array();

        foreach ($result as $row) {
            if (!in_array($row['role_id'], $roleIds)) {
                $roleIds[] = $row['role_id'];
            }

            if (!in_array($row['privilege_id'], $privilegeIds)) {
                $privilegeIds[] = $row['privilege_id'];
            }
        }

        return array('role_ids' => $roleIds, 'privilege_ids' => $privilegeIds);
    }
    public function showRolesAndPrivileges($pdo, $userId)
    {

        $privilegeManager = new PrivilegeManager_();

        // Pobierz ID zalogowanego użytkownika (zmień to na odpowiedni sposób w zależności od twojego systemu uwierzytelniania)
        $loggedInUserId = $_SESSION['user_id'];

        // Pobierz rolę i uprawnienia dla zalogowanego użytkownika
        $userData = $privilegeManager->getUserRoleAndPrivileges($loggedInUserId);

        // Zapisz rolę i uprawnienia w zmiennych
        $roleIds = $userData['role_ids'];
        $privilegeIds = $userData['privilege_ids'];

        echo '
        <!-- Wyświetl rolę i listę uprawnień dla zalogowanego użytkownika -->
        <h3>Rola:</h3>
        <ul>
            <?php foreach ($roleIds as $roleId): ?>
                <li>
                    <?php echo $roleId; ?>
                </li>
            <?php endforeach; ?>
        </ul>
            
        <h3>Lista uprawnień:</h3>
        <ul>
            <?php foreach ($privilegeIds as $privilegeId): ?>
                <li>
                    <?php echo $privilegeId; ?>
                </li>
            <?php endforeach; ?>
        </ul>';
    }

    public function show_messages()
    {
        $sql = "SELECT * FROM messages";
        $result = $this->db->prepare($sql);
        $messages = array();
        while ($row = $result->fetch()) {
            $messages[$row['id']] = $row['message'];
        }
        return $messages;
    }

    //funkcja do wyświetlania roli zalogowanego użytkownika
    public function get_role($login)
    {
        $login = $this->purifier->purify($login);

        try {
            $sql = "SELECT r.role_name FROM role r"
                . " INNER JOIN user_role ur ON ur.id_role = r.id"
                . " INNER JOIN user u ON u.id = ur.id_user"
                . " WHERE u.login = :login";

            $stmt = $this->db->prepare($sql);
            $stmt->execute(['login' => $login]);
            $role = $stmt->fetchColumn();
            if ($role) {
                echo "Rola: " . $role . "<br/>";
            } else {
                echo "Brak przypisanej roli dla użytkownika<br/>";
            }
        } catch (Exception $e) {
            print 'Exception' . $e->getMessage();
            echo 'Exception' . $e->getMessage();
        }
    }
    // funkcja do wyświetlania przywilejów zalogowanego użytkownika
    public function get_privileges($login)
    {
        $login = $this->purifier->purify($login);

        try {
            $sql = "SELECT p.name FROM privilege p"
                . " INNER JOIN role_privilege rp ON rp.privilege_id = p.id"
                . " INNER JOIN role r ON r.id = rp.id_role"
                . " INNER JOIN user_role ur ON ur.id_role = r.id"
                . " INNER JOIN user u ON u.id = ur.id_user"
                . " WHERE u.login = :login";

            $stmt = $this->db->prepare($sql);
            $stmt->execute(['login' => $login]);
            $privileges = $stmt->fetchAll();
            if ($privileges) {
                echo "Przywilej: " . "<br/>";
                foreach ($privileges as $privilege) {
                    echo "- " . $privilege['name'] . "<br/>";
                }
            } else {
                echo "Brak przypisanych przywilejów dla użytkownika<br/>";
            }
        } catch (Exception $e) {
            print 'Exception' . $e->getMessage();
            echo 'Exception' . $e->getMessage();
        }
    }


    public function get_all_roles()
    {
        try {
            $sql = "SELECT * FROM role";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $roles = $stmt->fetchAll();
            if ($roles) {
                echo "Role: " . "<br/>";
                foreach ($roles as $role) {
                    echo "- " . $role['role_name'] . "<br/>";
                }
            } else {
                echo "Brak ról<br/>";
            }
        } catch (Exception $e) {
            print 'Exception' . $e->getMessage();
            echo 'Exception' . $e->getMessage();
        }

    }

    public function get_all_privileges()
    {

        try {
            $sql = "SELECT * FROM privilege";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $privileges = $stmt->fetchAll();
            if ($privileges) {
                echo "Przywileje: " . "<br/>";
                foreach ($privileges as $privilege) {
                    echo "- " . $privilege['name'] . "<br/>";
                }
            } else {
                echo "Brak przywilejów <br/>";
            }
        } catch (Exception $e) {
            print 'Exception' . $e->getMessage();
            echo 'Exception' . $e->getMessage();
        }

    }

    public function show_all_roles_and_privileges()
    {
        echo "<hr/>";
        echo "Role i przywileje: <br/>";
        $this->get_all_roles();
        echo "<br/>";
        $this->get_all_privileges();
        echo "<hr/>";
    }

    public function get_roles_with_privileges($login)
    {
        $login = $this->purifier->purify($login);
        try {
            $sql = "SELECT r.role_name, p.name FROM role r"
                . " INNER JOIN role_privilege rp ON r.id = rp.id_role"
                . " INNER JOIN privilege p ON p.id = rp.privilege_id"
                . " INNER JOIN user_role ur ON ur.id_role = r.id"
                . " INNER JOIN user u ON u.id = ur.id_user"
                . " WHERE u.login = :login";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['login' => $login]);
            $data = $stmt->fetchAll();

            foreach ($data as $row) {
                $role = $row['role_name'];
                $privilege = $row['name'];
                $_SESSION[$role][$privilege] = 'YES';
            }

            $data['status'] = 'success';
            echo 'Roles and privileges set<br/>';
            return $data;
        } catch (Exception $e) {
            print 'Exception' . $e->getMessage();
            echo 'Exception' . $e->getMessage();
        }

        return [
            'status' => 'failed'
        ];
    }
    /*
        function post_new_role($login)
        {

            try {
                $db = new PDO('mysql:host=localhost;dbname=news', 'root', '');
                $role_name = $_POST['role_name'];
                $description = $_POST['description'];

                $id_role = $role_name;

                $sql = "INSERT INTO role(role_name, description) 
                VALUES (:role_name, :description)";

                $data = [
                    'role_name' => $role_name,
                    'description' => $description
                ];
                $db->prepare($sql)->execute($data);

                $id_role = $db->lastInsertId();
                $issue_time = date("Y-m-d");

                $sql = "INSERT INTO role_privilege(id_role, privilege_id, issue_time, expire_time) 
                    values (:id_role, :privilege_id, :issue_time, :expire_time)";
                $data = [
                    'id_role' => $id_role,
                    'privilege_id' => $privilege_id,
                    'issue_time' => $issue_time,
                    'expire_time' => NULL
                ];

                $db->prepare($sql)->execute($data);

            } catch (PDOException $e) {
                echo $e->getMessage();
            }

        }
    */
    // public function get_privileges2($login)
    // {
    //     $login = $this->purifier->purify($login);
    //     try {
    //         $sql = "SELECT p.id,p.name FROM privilege p"
    //             . " INNER JOIN user_privilege up ON p.id=up.id_privilege"
    //             . " INNER JOIN user u ON u.id=up.id_user"
    //             . " WHERE u.login=:login";
    //         $stmt = $this->db->prepare($sql);
    //         $stmt->execute(['login' => $login]);
    //         $data = $stmt->fetchAll();
    //         foreach ($data as $row) {
    //             $privilege = $row['name'];
    //             $_SESSION[$privilege] = 'YES';
    //         }
    //         $data['status'] = 'success';
    //         return $data;
    //     } catch (Exception $e) {
    //         print 'Exception' . $e->getMessage();
    //     }
    //     return [
    //         'status' => 'failed'
    //     ];
    // }


} // END OF CLASS