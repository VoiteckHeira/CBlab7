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
            $sql = "SELECT * FROM role";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $roles = array();
            while ($row = $stmt->fetch()) {
                $roles[$row['id']] = $row['role_name'];
            }
            return $roles;
        } catch (PDOException $e) {
            die();
        }
    }

    public function getAllPrivileges()
    {
        try {
            $sql = "SELECT * FROM privilege";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $privileges = array();
            while ($row = $stmt->fetch()) {
                $privileges[$row['id']] = $row['name'];
            }
            return $privileges;
        } catch (PDOException $e) {
            die();
        }
    }

    public function displayRolesAndPrivileges()
    {
        $roles = $this->getAllRoles();
        $privileges = $this->getAllPrivileges();

        if (!empty($roles) && !empty($privileges)) {
            echo "Role:<br>";
            foreach ($roles as $roleId => $roleName) {
                echo " - $roleName<br>";
            }
            echo "<br>Przywileje:<br>";
            foreach ($privileges as $privilegeId => $privilegeName) {
                echo " -- $privilegeName<br>";
            }
        } else {
            echo "Brak dostępnych ról i przywilejów.";
        }
    }
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

    public function show_privileges_v2($login)
    {
        $result = $this->check_privileges($login);
        if ($result['status'] === 'success') {
            foreach ($result as $key => $value) {
                if ($key !== 'status') {
                    $privilege = $value['name'];
                    echo " - " . $privilege . "<br>";
                }
            }
        } else {
            echo 'error';
        }

    }

    public function get_all_users()
    {

        try {
            $sql = "SELECT * FROM user";

            $stmt = $this->db->prepare($sql);
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


    public function popup($what)
    {
        if ($what == 'dodano') {
            echo '<script type="text/javascript">';
            echo 'alert("Dodano")';
            echo '</script>';
        } else if ($what == 'usunieto') {
            echo '<script type="text/javascript">';
            echo 'alert("Usunięto")';
            echo '</script>';
        } else if ($what == 'zedytowano') {
            echo '<script type="text/javascript">';
            echo 'alert("Zedytowano")';
            echo '</script>';
        } else if ($what == 'blad') {
            echo '<script type="text/javascript">';
            echo 'alert("Błąd")';
            echo '</script>';
        }

    }
    //funkcja do wyświetlania roli zalogowanego użytkownika

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

    public function add_privilege($privilege_name)
    {
        $privilege_name = $this->purifier->purify($privilege_name);
        $stmt = $this->db->prepare("INSERT INTO privilege(name) VALUES (:privilege_name)");
        $stmt->bindParam(':privilege_name', $privilege_name);

        try {
            $stmt->execute();
            $this->popup('dodano');
        } catch (PDOException $ex) {

            throw $ex;
        }
    }

    public function add_role($role_name)
    {
        $role_name = $this->purifier->purify($role_name);
        $stmt = $this->db->prepare("INSERT INTO role(role_name) VALUES (:role_name)");
        $stmt->bindParam(':role_name', $role_name);

        try {
            $stmt->execute();
            $this->popup('dodano');
        } catch (PDOException $ex) {

            throw $ex;
        }
    }

    public function delete_privilege($privilege_name)
    {
        $privilege_name = $this->purifier->purify($privilege_name);
        $stmt = $this->db->prepare("DELETE FROM privilege WHERE name = :privilege_name");
        $stmt->bindParam(':privilege_name', $privilege_name);

        try {
            $stmt->execute();
            $this->popup('usunieto');
        } catch (PDOException $ex) {

            throw $ex;
        }
    }
    public function delete_role($role_name)
    {
        $role_name = $this->purifier->purify($role_name);
        $stmt = $this->db->prepare("DELETE FROM role WHERE role_name = :role_name");
        $stmt->bindParam(':role_name', $role_name);

        try {
            $stmt->execute();
            $this->popup('usunieto');
        } catch (PDOException $ex) {

            throw $ex;
        }
    }

    public function add_role_privilege($role_name, $privilege_name)
    {
        $role_name = $this->purifier->purify($role_name);
        $privilege_name = $this->purifier->purify($privilege_name);
        $stmt = $this->db->prepare("INSERT INTO role_privilege(role_id, privilege_id) VALUES ((SELECT id FROM role WHERE role_name = :role_name), (SELECT id FROM privilege WHERE name = :privilege_name))");
        $stmt->bindParam(':role_name', $role_name);
        $stmt->bindParam(':privilege_name', $privilege_name);

        try {
            $stmt->execute();

        } catch (PDOException $ex) {

            throw $ex;
        }
    }

    public function delete_role_privilege($role_name, $privilege_name)
    {
        $role_name = $this->purifier->purify($role_name);
        $privilege_name = $this->purifier->purify($privilege_name);
        $stmt = $this->db->prepare("DELETE FROM role_privilege WHERE role_id = (SELECT id FROM role WHERE role_name = :role_name) AND privilege_id = (SELECT id FROM privilege WHERE name = :privilege_name)");
        $stmt->bindParam(':role_name', $role_name);
        $stmt->bindParam(':privilege_name', $privilege_name);

        try {
            $stmt->execute();
        } catch (PDOException $ex) {

            throw $ex;
        }
    }

    public function delete_message($id)
    {
        $id = $this->purifier->purify($id);
        $stmt = $this->db->prepare("DELETE FROM message WHERE id = :id");
        $stmt->bindParam(':id', $id);

        try {
            $stmt->execute();
            $this->popup('usunieto');
        } catch (PDOException $ex) {

            throw $ex;
        }
    }

    public function show_messages()
    {
        $stmt = $this->db->prepare("SELECT * FROM message");
        try {
            $stmt->execute();
            $messages = $stmt->fetchAll();
            if ($messages) {
                foreach ($messages as $message) {
                    echo "<div class='message'>";
                    echo "<div class='message_header'>";
                    echo "<div class='message_title'>" . $message['title'] . "</div>";
                    echo "<div class='message_date'>" . $message['date'] . "</div>";
                    echo "</div>";
                    echo "<div class='message_content'>" . $message['content'] . "</div>";
                    echo "<div class='message_author'>" . $message['author'] . "</div>";
                    echo "<div class='message_delete'><a href='?action=delete_message&id=" . $message['id'] . "'>Usuń</a></div>";
                    echo "</div>";
                }
            } else {
                echo "Brak wiadomości";
            }
        } catch (PDOException $ex) {

            throw $ex;
        }
    }









































    /*
     ****************************************************************************************************
     ************************************
     **************************
     *****************
     ********
     */
} //END OF CLASS