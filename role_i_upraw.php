<?php

session_start();

if (isset($_SESSION['session_expire'])) {
    if (time() - $_SESSION['session_expire'] > (30 * 60)) {
        session_unset();
        session_destroy();

        header("Location: index.php");
        exit();
    } else {
        $_SESSION['session_expire'] = time();
    }
}

if (isset($_REQUEST['logout'])) {
    unset($_SESSION['login']);
}

?>
<h5>
    <?php
    if (!empty($_SESSION['login'])) {
        echo $_SESSION['login'];
    } else {
        echo 'niezalogowany';
    }
    ?>
</h5>
<?php

include_once "classes/Page.php";
include_once "classes/Pdo.php";
include_once "classes/Db.php";
include_once "classes/Filter.php";
require './htmlpurifier-4.15.0/library/HTMLPurifier.auto.php';

$db = new Db("localhost", "news", "root", "");
require './htmlpurifier-4.15.0/library/HTMLPurifier.auto.php';
$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);

Page::display_header("Main page");
$Pdo = new Pdo_();

$conn = $db->getConnection();

if (!empty($_SESSION['login'])) {
    echo 'Zalogowany jako: </br>';
    echo $_SESSION['login'];

} else {
    echo 'niezalogowany';
}
?>


<?php
//$where_clause = "";
//$sql = "SELECT * FROM role" . $where_clause;
////$sql2 = "SELECT * FROM privileges" . $where_clause;
//$stmt = $db->pdo->prepare($sql);
////$stmt2->$db->pdo->prepare($sql2);
//$stmt->execute();
////$stmt2->execute();
//$roles = $stmt->fetchAll(PDO::FETCH_OBJ);
//$privileges = $stmt2->fetchAll(PDO::FETCH_OBJ);
//foreach ($roles as $role) {
//    //foreach ($privileges as $privilege) {
//    //    if ($role->id == $privilege->role_id) {
//    //        echo $privilege->id . '">' . $privilege->privilege_name . '<br>';
//    //    }
//    //}
//    echo $role->id . $role->role_name . '<br>';
//}
?>
<?php
// Pobierz listę ról i uprawnień z bazy danych
$sql = "SELECT r.role_name, p.name
        FROM role_privilege rp
        JOIN role r ON rp.id_role = r.id
        JOIN privilege p ON rp.id_privilege = p.id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo '<h5>List of Roles and Privileges:</h5>';
    echo '<ul>';
    while ($row = $result->fetch_assoc()) {
        echo '<li>' . $row['role_name'] . ': ' . $row['name'] . '</li>';
    }
    echo '</ul>';
} else {
    echo 'No roles and privileges found.';
}

$result->free_result();
?>