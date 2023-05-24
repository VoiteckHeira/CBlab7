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

<h3>Wszyscy użytkownicy</h3>
<?php
$Priv->get_all_users();
?>

<h2>Wszystkie role i uprawnienia: </h2>

<?php
$Priv->displayRolesAndPrivileges();
?>
<br />
<hr />
<br />
<h2>Dodaj rolę lub uprawnienie: </h2>
<?php
//czesc do przesłąnia do funkcji - roli
if (isset($_REQUEST['add_role'])) {
    $role = $_REQUEST['role'];
    $Priv->add_role($role);
}

//czesc do przeslania do funkcji - uprawnienia
if (isset($_REQUEST['add_privilege'])) {
    $privilege = $_REQUEST['privilege'];
    $Priv->add_privilege($privilege);
}

if (isset($_REQUEST['add_role_privilege'])) {
    $role = $_REQUEST['role'];
    $privilege = $_REQUEST['privilege'];
    $Priv->add_role_privilege($role, $privilege);
}

if (isset($_REQUEST['delete_privilege'])) {
    $privilege = $_REQUEST['privilege'];
    $Priv->delete_privilege($privilege);
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Dodaj uprawnienie</title>
</head>

<body>

    Dodanie nowej roli:<br />
    <form action="role_i_upraw.php" method="post">
        <input type="text" name="role" placeholder="Podaj nazwę roli">
        <input type="submit" name="add_role" value="Dodaj rolę">
    </form>

    Dodanie nowego uprawnienia:<br />
    <form action="role_i_upraw.php" method="post">
        <input type="text" name="privilege" placeholder="Podaj nazwę uprawnienia">
        <input type="submit" name="add_privilege" value="Dodaj uprawnienie">
    </form>

    Usuwanie uprawnienia:<br />
    <form action="role_i_upraw.php" method="post">
        <input type="text" name="privilege" placeholder="Podaj nazwę uprawnienia">
        <input type="submit" name="delete_privilege" value="Usuń uprawnienie">
    </form>

    Usuwanie roli:<br />
    <form action="role_i_upraw.php" method="post">
        <input type="text" name="role" placeholder="Podaj nazwę roli">
        <input type="submit" name="delete_role" value="Usuń rolę">
    </form>

    Dodanie uprawnienia do roli:<br />
    <form action="role_i_upraw.php" method="post">
        <input type="text" name="role" placeholder="Podaj nazwę roli">
        <input type="text" name="privilege" placeholder="Podaj nazwę uprawnienia">
        <input type="submit" name="add_role_privilege" value="Dodaj uprawnienie do roli">
    </form>

    Dodawanie uprawnienia do użytkownika:<br />
    <form action="role_i_upraw.php" method="post">
        <input type="text" name="login" placeholder="Podaj nazwę użytkownika">
        <input type="text" name="privilege" placeholder="Podaj nazwę uprawnienia">
        <input type="submit" name="add_user_privilege" value="Dodaj uprawnienie do użytkownika">
    </form>
    Dodanie roli dla użytkownika:<br />
    <form action="role_i_upraw.php" method="post">
        <input type="text" name="login" placeholder="Podaj nazwę użytkownika">
        <input type="text" name="role" placeholder="Podaj nazwę roli">
        <input type="submit" name="add_user_role" value="Dodaj rolę do użytkownika">
    </form>
    Usuwanie uprawnienia z roli:<br />
    <form action="role_i_upraw.php" method="post">
        <input type="text" name="role" placeholder="Podaj nazwę roli">
        <input type="text" name="privilege" placeholder="Podaj nazwę uprawnienia">
        <input type="submit" name="delete_role_privilege" value="Usuń uprawnienie z roli">
    </form>
    Usuwanie roli z użytkownika:<br />
    <form action="role_i_upraw.php" method="post">
        <input type="text" name="login" placeholder="Podaj nazwę użytkownika">
        <input type="text" name="role" placeholder="Podaj nazwę roli">
        <input type="submit" name="delete_user_role" value="Usuń rolę z użytkownika">
    </form>
    <form method="post">
        <label for="user_id">Wybierz użytkownika:</label>
        <select id="user_id" name="user_id">
            <?php foreach ($users as $user): ?>
                <option value="<?php echo $user['id']; ?>" <?php if ($user['id'] == ($_GET['user_id'] ?? 0))
                       echo 'selected'; ?>><?php echo $user['login']; ?></option>
            <?php endforeach; ?>
        </select>

        <?php if (!empty($_GET['user_id'])): ?>
            <label for="role_id">Wybierz rolę do usunięcia:</label>
            <select id="role_id" name="role_id">
                <?php
                $roles = $pdo->get_user_roles($_GET['user_id']);
                foreach ($roles as $role): ?>
                    <option value="<?php echo $role['id']; ?>"><?php echo $role['name']; ?></option>

                <?php endforeach; ?>
            </select>
            <input type="submit" value="Usuń rolę użytkownikowi">
        <?php endif; ?>
    </form>





    Usuwanie uprawnienia z użytkownika:<br />
    <form action="role_i_upraw.php" method="post">
        <input type="text" name="login" placeholder="Podaj nazwę użytkownika">
        <input type="text" name="privilege" placeholder="Podaj nazwę uprawnienia">
        <input type="submit" name="delete_user_privilege" value="Usuń uprawnienie z użytkownika">
    </form>



    <br />
    <hr />

    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <label for="permission_name">Nazwa uprawnienia:</label>
        <input type="text" id="permission_name" name="permission_name"><br>
        <input type="submit" value="Dodaj">
    </form>



    <P>Navigation</P>
    <?php
    Page::display_navigation();
    ?>

</body>