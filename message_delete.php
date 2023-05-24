<?php
include_once "session.php";
make_session();
?>



<?php
include_once "classes/Page.php";
include_once "classes/Pdo.php";

Page::display_header("Remove message");

if (isset($_POST['id'])) {
    $pdo = new Pdo_();

    $deleted = $pdo->delete_message($_POST['id']);

    if (!$deleted) {
        echo 'MESSAGE NOT FOUND';
    } else {
        echo 'MESSAGE DELETED';
    }
}

?>
<hr>

<hr>
<P>Navigation</P>
<?php Page::display_navigation(); ?>
</body>

</html>