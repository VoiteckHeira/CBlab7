<?php
include_once "classes/Pdo.php";
include_once "classes/Db.php";
include_once "classes/Privileges.php";
function make_session()
{
    session_start();
    $Priv = new PrivilegeManager_();
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
    if (!empty($_SESSION['login'])) {
        echo 'Zalogowany jako: </br>';
        echo $_SESSION['login'];
        echo '</br>';
        $login = $_SESSION['login'];
        $Priv->get_role($login);
        $Priv->get_privileges($login);
        $Priv->check_privileges($login);
    } else {
        echo 'niezalogowany';
    }
}

?>