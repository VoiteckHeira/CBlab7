<?php

require_once './classes/Pdo.php';
require_once './htmlpurifier-4.15.0/library/HTMLPurifier.auto.php';

class PrivilegeManager
{
    private $db;
    private $purifier;
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

        $privilegeManager = new PrivilegeManager($pdo);

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


}