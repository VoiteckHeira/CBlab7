<?php
include_once "session.php";
make_session();
$login = $_SESSION['login'];
?>

<?php
include_once "classes/Page.php";
include_once "classes/Db.php";
include_once "classes/Filter.php";
require './htmlpurifier-4.15.0/library/HTMLPurifier.auto.php';
include_once 'classes/Privileges.php';
Page::display_header("Messages");


// Create a new Db object
$db = new Db("localhost", "news", "root", "");
require './htmlpurifier-4.15.0/library/HTMLPurifier.auto.php';
$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);
$Priv = new PrivilegeManager_();

if (isset($_REQUEST['delete_message'])) {
    $id = $_REQUEST['id'];
    $Priv->delete_message($id);
}

// Adding new message
if (isset($_REQUEST['add_message'])) {
    $name = $purifier->purify($_REQUEST['name']);
    $type = $_REQUEST['type'];
    $content = $purifier->purify($_REQUEST['content']);
    if (!$db->addMessage($name, $type, $content))
        echo "Adding new message failed";


    $stmt = $db->pdo->prepare("INSERT INTO message (name, type, message, deleted) VALUES (:name, :type, :content, 0)");
    $t = Filter::sanitizeData($name, 'str');
    $tt = Filter::sanitizeData($type, 'str');
    $ttt = Filter::sanitizeData($content, 'str');
    $stmt->bindParam(':name', $t);
    $stmt->bindParam(':type', $tt);
    $stmt->bindParam(':content', $ttt);
    if (!$stmt->execute())
        echo "Adding new message failed";
}
?>
<!---------------------------------------------------------------------->
<hr>

<P> Messages</P>
<ol>
    <?php
    $Priv->show_messages();
    echo "<hr />";

    ?>
</ol>

<!---------------------------------------------------------------------->



<!---------------------------------------------------------------------->
<hr>
<P>Messages filtering</P>
<form method="post" action="messages.php">
    <table>
        <tr>
            <td>Title contains: </td>
            <td>
                <label for="name"></label>
                <input required type="text" name="string" id="string" size="80" />
            </td>
            <td>Type: </td>
            <td>
                <select name="type" id="type">
                    <option value="public">public</option>
                    <option value="private">private</option>
                </select>
            </td>
        </tr>
    </table>
    <input type="submit" id="submit" value="Find messages" name="filter_messages">
</form>

<!--------------------------------------------------------------------->
<hr>
<P>Messages editing</P>
<form method="post" action="message_edit.php">
    <table>
        <tr>
            <td>Input id of message to edit: </td>
            <td>
                <label for="id"></label>
                <input required type="number" name="id" id="id" size="20" />
            </td>
        </tr>
    </table>
    <input type="submit" id="submit" value="Edit message" name="edit_message">
</form>
<!--------------------------------------------------------------------->
<hr>
<P>Messages deleting</P>
<form method="post" action="messages.php">
    <table>
        <tr>
            <td>Input id of message to delete: </td>
            <td>
                <label for="id"></label>
                <input required type="number" name="id" id="id" size="20" />
            </td>
        </tr>
    </table>
    <input type="submit" id="submit" value="Delete message" name="delete_message">


    <hr>
    <P>Navigation</P>
    <?php
    Page::display_navigation();
    ?>

    </body>

    </html>