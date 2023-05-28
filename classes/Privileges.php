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

    /*  NIE DZIAŁA I BUGUJE
        public function show_thing($login)
        {
            $allowed_functions = [
                'getAllRoles',
                'add_role',
                'delete_role',
                'add_privilege',
                'delete_privilege',
                'add_role_privilege',
                'delete_role_privilege',
                'get_all_users',
                'get_role',
                'check_privileges',
                'displayRolesAndPrivileges',
                'add_message',
                'delete_message',
                'edit_message',
                'display_messages',
            ];
            $user_permissions = [];
            $result = $this->check_privileges($login);
            if ($result['status'] === 'success') {
                foreach ($result as $key => $value) {
                    if ($key !== 'status') {
                        $privilege = $value['name'];
                        $user_permissions[] = $privilege;
                    }
                }
            } else {
                echo "Eroor";
            }

            //$user_permissions = $_SESSION['user_permissions'];



            foreach ($allowed_functions as $function_name) {
                if (in_array($function_name, $user_permissions)) {
                    call_user_func([$this, $function_name], $login);
                }
            }


        }*/



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
                echo " $roleId. $roleName<br>";
            }
            echo "<br>Przywileje:<br>";
            foreach ($privileges as $privilegeId => $privilegeName) {
                echo " $privilegeId. $privilegeName<br>";
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
            $privileges = [];

            foreach ($data as $row) {
                $privilege = $row['name'];
                $_SESSION[$privilege] = 'YES';
                $privileges[] = $privilege;
            }
            $data['status'] = 'success';
            $data['privileges'] = $privileges;

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
            $stmt->execute();
            $user = $stmt->fetchAll();
            if ($user) {
                foreach ($user as $us) {
                    echo $us['id'] . " " . $us['login'] . "<br/>";
                }
            } else {
                echo "Brak użytkownika<br/>";
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
                    echo "- " . $privilege['id'] . " " . $privilege['name'] . "<br/>";

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
        $stmt = $this->db->prepare("INSERT INTO role_privilege(id_role, privilege_id) VALUES ((SELECT id FROM role WHERE role_name = :role_name), (SELECT id FROM privilege WHERE name = :privilege_name))");
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
        $stmt = $this->db->prepare("DELETE FROM role_privilege WHERE id_role = (SELECT id FROM role WHERE role_name = :role_name) AND privilege_id = (SELECT id FROM privilege WHERE name = :privilege_name)");
        $stmt->bindParam(':role_name', $role_name);
        $stmt->bindParam(':privilege_name', $privilege_name);

        try {
            $stmt->execute();
        } catch (PDOException $ex) {

            throw $ex;
        }
    }

    public function add_user_role($login, $role_name)
    {
        $login = $this->purifier->purify($login);
        $role_name = $this->purifier->purify($role_name);
        $stmt = $this->db->prepare("INSERT INTO user_role(id_user, id_role) VALUES ((SELECT id FROM user WHERE login = :login), (SELECT id FROM role WHERE role_name = :role_name))");
        $stmt->bindParam(':login', $login);
        $stmt->bindParam(':role_name', $role_name);

        //$stmt = $this->db->prepare("INSERT INTO user_privilege(id_user, id_privilege) VALUES ((SELECT id FROM user WHERE login = :login), (SELECT id FROM privilege WHERE )");
        try {
            $stmt->execute();
        } catch (PDOException $ex) {
            echo "Nie można dodać roli do użytkownika";
            echo $ex;
            throw $ex;
        }
    }

    public function delete_user_role($login, $role_name)
    {
        $login = $this->purifier->purify($login);
        $role_name = $this->purifier->purify($role_name);
        $stmt = $this->db->prepare("DELETE FROM user_role WHERE id_user = (SELECT id FROM user WHERE login = :login) AND id_role = (SELECT id FROM role WHERE role_name = :role_name)");
        $stmt->bindParam(':login', $login);
        $stmt->bindParam(':role_name', $role_name);

        try {
            $stmt->execute();
        } catch (PDOException $ex) {

            throw $ex;
        }
    }

    public function add_user_privilege($login, $privilege_name)
    {
        $login = $this->purifier->purify($login);
        $privilege_name = $this->purifier->purify($privilege_name);
        $stmt = $this->db->prepare("INSERT INTO user_privilege(id_user, id_privilege) VALUES ((SELECT id FROM user WHERE login = :login), (SELECT id FROM privilege WHERE name = :privilege_name))");
        $stmt->bindParam(':login', $login);
        $stmt->bindParam(':privilege_name', $privilege_name);

        try {
            $stmt->execute();
        } catch (PDOException $ex) {

            throw $ex;
        }
    }

    public function delete_user_privilege($login, $privilege_name)
    {
        $login = $this->purifier->purify($login);
        $privilege_name = $this->purifier->purify($privilege_name);
        $stmt = $this->db->prepare("DELETE FROM user_privilege WHERE id_user = (SELECT id FROM user WHERE login = :login) AND id_privilege = (SELECT id FROM privilege WHERE name = :privilege_name)");
        $stmt->bindParam(':login', $login);
        $stmt->bindParam(':privilege_name', $privilege_name);

        try {
            $stmt->execute();
        } catch (PDOException $ex) {

            throw $ex;
        }
    }




    public function show_messages()
    {
        $stmt = $this->db->prepare("SELECT * FROM message");
        try {
            $where_clause = "";
            // filtering messages
            if (isset($_REQUEST['filter_messages'])) {
                $string = $_REQUEST['string'];
                $type = $_REQUEST['type'];
                if (in_array($type, ['public', 'private'])) {
                    $where_clause = " WHERE name LIKE :string AND type = :type";
                }
            }

            $sql = "SELECT * from message" . $where_clause; //biala_lista
            $stmt = $this->db->prepare($sql);
            if (isset($_REQUEST['filter_messages'])) {
                $string = "%" . $_REQUEST['string'] . "%";
                $type = $_REQUEST['type'];
                if (in_array($type, ['public', 'private'])) {
                    $tttt = Filter::sanitizeData($string, 'str');
                    $ttttt = Filter::sanitizeData($type, 'str');
                    $stmt->bindParam(':string', $tttt);
                    $stmt->bindParam(':type', $ttttt);
                }
            }

            $stmt->execute();
            $messages = $stmt->fetchAll(PDO::FETCH_OBJ);

            echo '<table>';
            foreach ($messages as $msg):
                ?>
                <table>
                    <tr>
                        <td>
                            <?php echo $msg->id ?>
                        </td>
                        <td>
                            <?php echo $msg->name ?>
                        </td>
                        <td>
                            <?php echo $msg->message ?>
                        </td>
                    </tr>
                </table>

                <?php
                echo '</table>';

            endforeach;

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







































    /*
     ****************************************************************************************************
     ************************************
     **************************
     *****************
     ********
     */
} //END OF CLASS