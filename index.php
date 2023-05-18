<?php
include_once "session.php";
make_session();
?>

<?php

include_once "classes/Page.php";
//include_once "classes/privileges/create_role.php";
include_once "classes/Db.php";
include_once "classes/Filter.php";
require './htmlpurifier-4.15.0/library/HTMLPurifier.auto.php';

$db = new Db("localhost", "news", "root", "");
require './htmlpurifier-4.15.0/library/HTMLPurifier.auto.php';
$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);

Page::display_header("Main page");


// Create a new Db object

if (isset($_REQUEST['add_user'])) {
    $login = $_REQUEST['login'];
    $email = $_REQUEST['email'];
    $password = $_REQUEST['password'];
    $password2 = $_REQUEST['password2'];
    $role = $_REQUEST['role'];
    $twofa = !empty($_REQUEST['2fa']);

    if ($password == $password2) {
        $Pdo->add_user($login, $email, $password, $password2, $role, $twofa);
    } else {
        echo 'Passwords doesn\'t match';
    }
}

if (isset($_REQUEST['log_user_in'])) {
    $code = $_REQUEST['code'];
    $login = $_SESSION['login'];
    if ($Pdo->log_2F_step2($login, $code)) {
        echo 'You are logged in as: ' . $_SESSION['login'];
        $_SESSION['logged'] = 'YES';
    }
}


if (isset($_REQUEST['change_password'])) {
    $old_password = $_REQUEST['old_password'];
    $password = $_REQUEST['password'];
    $password2 = $_REQUEST['password2'];

    $Pdo->change_password($old_password, $password, $password2);
}




?>

<hr>
<H2> Main page</H2>
<!-- <button onclick="show_all_roles()">Wyświetl role i uprawnienia</button> -->



<!---------------------------------------------------------------------->
<hr>
<P> Register new user</P>
<form method="post" action="index.php">
    <table>
        <tr>
            <td>login</td>
            <td>
                <label for="name"></label>
                <input required type="text" name="login" id="login" size="40" />
            </td>
        </tr>
        <tr>
            <td>email</td>
            <td>
                <label for="name"></label>
                <input required type="text" name="email" id="email" size="40" />
            </td>
        </tr>
        <tr>
            <td>password</td>
            <td>
                <label for="name"></label>
                <input required type="text" name="password" id="password" size="40" />
            </td>
        </tr>
        <tr>
            <td>repeat password</td>
            <td>
                <label for="name"></label>
                <input required type="text" name="password2" id="password2" size="40" />
            </td>
        </tr>
        <tr>
            <td>role</td>
            <td>
                <label for="role"></label>
                <?php
                $where_clause = "";
                $sql = "SELECT * FROM role" . $where_clause;
                $stmt = $db->pdo->prepare($sql);
                $stmt->execute();
                $roles = $stmt->fetchAll(PDO::FETCH_OBJ);
                foreach ($roles as $role) {
                    echo '<input required type="radio" id="role" name="role" value="' . $role->id . '">' . $role->role_name . '<br>';
                }
                //<select name="role" id="role" name="getRolesRegister">
                //    <option value="1">Moderator</option>
                //    <option value="2">Admin</option>
                //    <option value="3">User</option>
                //    <option value="4">New</option>
                //</select>
                ?>
            </td>
        </tr>
        <tr>
        <tr>
            <td>turn on 2fa?</td>
            <td>
                <label for="2fa"></label>
                <input type="checkbox" id="2fa" name="2fa" />
            </td>
        </tr>
    </table>
    <input type="submit" id="submit" value="Create account" name="add_user">
</form>
<!---------------------------------------------------------------------->

<?php if (empty($_SESSION['login'])): ?>

    <hr>
    <P> Log in</P>
    <form method="post" action="login.php">
        <table>
            <tr>
                <td>login</td>
                <td>
                    <label for="name"></label>
                    <input required type="text" name="login" id="login" size="40" value="" />
                </td>
            </tr>
            <tr>
                <td>password</td>
                <td>
                    <label for="name"></label>
                    <input required type="text" name="password" id="password" size="40" value="" />
                </td>
            </tr>
        </table>
        <input type="submit" id="submit" value="Log in" name="log_user_in">
    </form>
<?php else: ?>
    <hr>
    <P> Change password </P>
    <form method="post" action="index.php">
        <table>
            <tr>
                <td>old password</td>
                <td>
                    <input required type="text" name="old_password" id="old_password" size="40" value="" />
                </td>
            </tr>
            <tr>
                <td>password</td>
                <td>
                    <input required type="text" name="password" id="password" size="40" value="" />
                </td>
            </tr>
            <tr>
                <td>password2</td>
                <td>
                    <input required type="text" name="password2" id="password2" size="40" value="" />
                </td>
            </tr>
        </table>
        <input type="submit" id="submit" value="Change password" name="change_password">
    </form>

    <form method="post" action="index.php">

        <input type="submit" id="submit" value="Logout" name="logout">

    </form>
    <hr>
    <?php
    include_once "classes/Page.php";
    Page::display_header("Main page");
    ?>
    <H2> Main page</H2>
    <?php
    Page::display_navigation();

?>
<?php endif; ?>
<hr>

<!-- </body>-->
<!--</html>-->