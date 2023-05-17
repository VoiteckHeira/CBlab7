<?php
//include_once "./session.php";
//make_session();
?>

<?php
//create form to create new role and send to database
include_once "./classes/Pdo.php";
include_once "./classes/Db.php";
?>

<hr>
<P> Create new role </P>
<form method="post" action="create_role.php">
    <table>
        <tr>
            <td>Role name</td>
            <td>
                <label for="name"></label>
                <input required type="text" name="role_name" id="role_name" size="40" value="" />
            </td>
        </tr>
        <tr>
            <td>Role description</td>
            <td>
                <label for="name"></label>
                <input required type="text" name="description" id="description" size="40" value="" />
            </td>
        </tr>
        <tr>
            <td>Privileges</td>
            <td>
                <label for="privileges"></label>
                <?php
                $where_clause = "";
                $sql = "SELECT * FROM role" . $where_clause;
                $stmt = $db->pdo->prepare($sql);
                $stmt->execute();
                $privileges = $stmt->fetchAll(PDO::FETCH_OBJ);
                foreach ($privileges as $privilege) {
                    echo '<input type="check" name="password" id="password" size="40" value=""' . $privilege->id . '">' . $privilege->name . '<br>';
                }
                ?>

            </td>
        </tr>
    </table>
    <input type="submit" id="submit" value="Confirm" name="create_new_role">
</form>

<?php
function post_new_role()
{

    try {
        $db = new PDO('mysql:host=localhost;dbname=news', 'root', '');
        $role_name = $_POST['role_name'];
        $description = $_POST['description'];

        $id_role = $role_name;

        $sql = "INSERT INTO role(role_name, description) 
            VALUES (:role_name, :description)";

        $data = [
            'role_name' => $role_name,
            'description' => $description
        ];
        $db->prepare($sql)->execute($data);

        $id_role = $db->lastInsertId();
        $issue_time = date("Y-m-d");

        $sql = "INSERT INTO role_privilege(id_role, privilege_id, issue_time, expire_time) 
                values (:id_role, :privilege_id, :issue_time, :expire_time)";
        $data = [
            'id_role' => $id_role,
            'privilege_id' => $privilege_id,
            'issue_time' => $issue_time,
            'expire_time' => NULL
        ];

        $db->prepare($sql)->execute($data);

    } catch (PDOException $e) {
        echo $e->getMessage();
    }

}
?>