

add_premission.php

<?php
include_once "classes/Page.php";
session_start();

if (isset($_SESSION['session_expire'])) {
    if (time() - $_SESSION['session_expire'] > (60 * 5)) {
        session_destroy();
    } else {
        $_SESSION['session_expire'] = time();
    }
}

?><h5><?php
    if (!empty($_SESSION['login'])) {
        echo $_SESSION['login'];
    } else {
        echo 'niezalogowany';
    }
include_once "classes/Pdo.php";
//require_once 'Pdo_.php';
$db = new Pdo_();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['permission_name'])) {
        $permission_name = $_POST['permission_name'];
        $db->add_permission($permission_name);
        echo "\n Dodano nowe uprawnienie: " . $permission_name;
    } else {
        echo "Nie podano nazwy uprawnienia.";
    }
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Dodaj uprawnienie</title>
</head>
<body>
    <h1>Dodaj uprawnienie</h1>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <label for="permission_name">Nazwa uprawnienia:</label><br>
        <input type="text" id="permission_name" name="permission_name"><br>
        <input type="submit" value="Dodaj">
    </form>
    <!--------------------------------------------------------------------->

<hr>
<P>Navigation</P>
<?php
Page::display_navigation();
?>
</body>
</html>

W PDO
<?php
public function add_permission($permission_name) {
        $stmt = $this->db->prepare("INSERT INTO permissions (name) VALUES (:permission_name)");
        $stmt->bindParam(':permission_name', $permission_name);
        
        try {
            $stmt->execute();
        } catch(PDOException $e) {
            // Możesz tutaj obsłużyć wyjątek, na przykład wyświetlić komunikat o błędzie
            throw $e;
        }
    }
?>