<!DOCTYPE html>
<html>

<head>
    <title>Formularz rejestracji</title>
</head>

<body>

    <?php
    // połączenie z bazą danych
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "news";

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // pobranie listy ról z bazy danych
    $sql = "SELECT * FROM role";
    $result = $conn->query($sql);

    $roles = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $roles[$row["id"]] = $row["role_name"];
        }
    }

    // zapisanie danych z formularza do bazy danych
    if (isset($_POST['add_user'])) {
        $login = $_POST['login'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $password2 = $_POST['password2'];
        $role_id = $_POST['role'];
        $twofa = isset($_POST['2fa']) ? 1 : 0;

        if ($password != $password2) {
            echo "Hasła nie pasują do siebie!";
        } else {
            $sql = "INSERT INTO users (login, email, password, role_id, twofa) VALUES ('$login', '$email', '$password', $role_id, $twofa)";
            if ($conn->query($sql) === TRUE) {
                echo "Użytkownik został dodany do bazy danych.";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    }
    ?>

    <h2>Formularz rejestracji</h2>
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
                    <input required type="password" name="password" id="password" size="40" />
                </td>
            </tr>
            <tr>
                <td>repeat password</td>
                <td>
                    <label for="name"></label>
                    <input required type="password" name="password2" id="password2" size="40" />
                </td>
            </tr>
            <tr>
                <td>role</td>
                <td>
                    <label for="role"></label>
                    <select name="role" id="role">
                        <?php
                        $sql = "SELECT * FROM role";
                        $result = mysqli_query($conn, $sql);
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<option value='" . $row['id'] . "'>" . $row['role_name'] . "</option>";
                            }
                        }
                        ?>
                    </select>
                </td>
            </tr>
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