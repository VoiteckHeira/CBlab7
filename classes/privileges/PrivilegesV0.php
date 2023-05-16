<?php
require './htmlpurifier-4.15.0/library/HTMLPurifier.auto.php';
require '../classes/Pdo.php';

class PrivilegesAndRoles
{
    private $db;
    private $purifier;
    private $privileges;
    private $roles;

    public function __construct($db)
    {
        $this->db = $db;
        $config = HTMLPurifier_Config::createDefault();
        $this->purifier = new HTMLPurifier($config);
        try {
            $this->db = new PDO('mysql:host=localhost;dbname=news', 'root', '');
        } catch (PDOException $e) {
            die();
        }
    }

    public function getPrivileges($login)
    {
        $login = $_SESSION['login'];
        try {
            $sql = "SELECT * FROM privileges";
            $result = $this->db->prepare($sql);

            $privileges = array();
            while ($row = $result->fetch()) {
                $privileges[$row['id']] = $row['name'];
            }
            return $privileges;
        } catch (PDOException $e) {
            die();
        }
    }

    public function getRolesRegister()
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

    public function getRolesToForm()
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

    public function getPrivilegesForRole($id_role)
    {
        $query = "SELECT * FROM role_privilege WHERE id_role = $id_role";
        $result = $this->db->query($query);
        $privileges = array();
        while ($row = $result->fetch()) {
            $privileges[$row['id_privilege']] = $row['id_privilege'];
        }
        return $privileges;
    }

    public function getPrivilegesForUser($id_user)
    {
        $query = "SELECT * FROM user_privilege WHERE id_user = $id_user";
        $result = $this->db->query($query);
        $privileges = array();
        while ($row = $result->fetch()) {
            $privileges[$row['id_privilege']] = $row['id_privilege'];
        }
        return $privileges;
    }

    public function getRolesForUser($id_user)
    {
        $query = "SELECT * FROM user_role WHERE id_user = $id_user";
        $result = $this->db->query($query);
        $roles = array();
        while ($row = $result->fetch()) {
            $roles[$row['id_role']] = $row['id_role'];
        }
        return $roles;
    }


}