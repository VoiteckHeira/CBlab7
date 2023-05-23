<?php
include_once "session.php";
make_session();
?>



<?php

include_once "classes/Page.php";
include_once "classes/Pdo.php";
include_once "classes/Db.php";
include_once "classes/Filter.php";
include_once "classes/Privileges.php";
require './htmlpurifier-4.15.0/library/HTMLPurifier.auto.php';

$db = new Db("localhost", "news", "root", "");
require './htmlpurifier-4.15.0/library/HTMLPurifier.auto.php';
$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);

Page::display_header("Main page");
$Pdo = new Pdo_();
$Priv = new PrivilegeManager_();


?>
<h2>Wszystkie role i uprawnienia: </h2>

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