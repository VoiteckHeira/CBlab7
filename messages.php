<?php

session_start();

if (isset($_SESSION['session_expire'])) {
    if (time() - $_SESSION['session_expire'] > (30 * 60)) {
        session_unset();
        session_destroy();

        header("Location: index.php");
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
include_once "classes/Db.php";
include_once "classes/Filter.php";
require './htmlpurifier-4.15.0/library/HTMLPurifier.auto.php';
Page::display_header("Messages");

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

// Create a new Db object
$db = new Db("localhost", "news", "root", "");
require './htmlpurifier-4.15.0/library/HTMLPurifier.auto.php';
$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);

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
    $where_clause = "";
    // filtering messages
    if (isset($_REQUEST['filter_messages'])) {
        $string = $_REQUEST['string'];
        $type = $_REQUEST['type'];
        if (in_array($type, ['public', 'private'])) {
            $where_clause = " WHERE name LIKE :string AND type = :type";
        }
    }

    $sql = "SELECT * from message" . $where_clause; //biala_lista
    $stmt = $db->pdo->prepare($sql);
    if (isset($_REQUEST['filter_messages'])) {
        $string = "%" . $_REQUEST['string'] . "%";
        $type = $_REQUEST['type'];
        if (in_array($type, ['public', 'private'])) {
            $tttt = Filter::sanitizeData($string, 'str');
            $ttttt = Filter::sanitizeData($type, 'str');
            $stmt->bindParam(':string', $tttt);
            $stmt->bindParam(':type', $ttttt);
        }
    }

    $stmt->execute();
    $messages = $stmt->fetchAll(PDO::FETCH_OBJ);

    foreach ($messages as $msg):
        echo $msg->id . ". " . $msg->message . "<br>";
    endforeach;
    ?>
</ol>

<!---------------------------------------------------------------------->
<hr>
<P> Messages</P>
<?php
$where_clause = "";
// filtering messages+
if (isset($_REQUEST['filter_messages'])) {
    $string = $_REQUEST['string'];
    $where_clause = " and name LIKE '%" . $string . "%'";
}
$sql = "SELECT * from message WHERE deleted=0 " . $where_clause;
echo $sql;
echo "<BR/><BR/>";
$messages = $db->select($sql);
if (count($messages)) {
    echo '<table>';
    $counter = 1;
    foreach ($messages as $msg): //returned as objects
        ?>
        <tr>
            <td>
                <?php echo $counter++ ?>
            </td>
            <td>
                <?php echo $msg->name ?>
            </td>
            <td>
                <?php echo $msg->message ?>
            </td>
            <form method="post" action="message_action.php">
                <input type="hidden" name="message_id" id="message_id" value="<?php echo $msg->id ?>" />
                <?php
                if (isset($_SESSION['delete message']))
                    echo '<td><input type="submit" id= "submit" value="Delete" name="delete_message"></td>';
                if (isset($_SESSION['edit message']))
                    echo '<td><input type="submit" id= "submit" ="Edit" name="edit_message"></td>';
                ?>
            </form>
        </tr>
        <?php
    endforeach;
    echo '</table>';
} else {
    echo "No messages available";
}
?>


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
<P>Navigation</P>
<?php
Page::display_navigation();
?>

</body>

</html>