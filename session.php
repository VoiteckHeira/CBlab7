<?php
include_once "classes/Pdo.php";
include_once "classes/Db.php";
function make_session()
{
    session_start();
    $db = new Db("localhost", "news", "root", "");
    $Pdo = new Pdo_();
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
        $Pdo->get_role($login);
        $Pdo->get_privileges($login);

    } else {
        echo 'niezalogowany';
    }
}

?>