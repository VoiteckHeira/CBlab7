/*
-- konto
0. rola admina bedzie mogła wyświetlać role i uprawnienia (dodać nowe uprawnienie do tego) i tylko admin bedzie mógł
tworzyć nowe role i uprawnienia
1. funkcja do pobierania z bazy listy utworzonych ról i wyświetlania ich jako lista do zaznaczenia w formularzu
tworzenia konta
2. funkcja do pobierania z bazy uprawnienia przypisanego do konta i wyświetlania go pod nazwą.
3. fukcja do pobierania z bazy listy utworzonych uprawnień i wyświetlania ich przy roli. w zalogowanym koncie


--listy
1. funkcja do pobierania z bazy listy wszystkich ról i wyświetlania ich jako lista
2. funckja do pobierania z bazy listy wszystkich uprawnień i wyświetlania ich jako lista pod uprawnieniami
3.

-- w bazie
== dodać odpowiednie tabele
0. wyczyścic tabele z uprawnieniami i rolami i ponumerować od nowa
1. tabela z uprawnieniami
2. tabela z rolami
3. porobić powiązania
4. dodać uprawnienie dla roli admin o tworzeniu, usuwaniu i edycji uprawnień
5. dodać uprawnienie dla roli admin o tworzeniu, usuwaniu i edycji ról
6. dodać uprawnienie dla roli admin o przypisywaniu uprawnień do ról


-- w formularzu rejestracji
1. dodać pole do wyboru roli
2. dodać automatyczne przypisanie roli i uprawnień do konta
3. domyślnie wybrany jest rola 'new'

-- po zalogowaniu
1. dodać wyświetlanie roli i uprawnień przy nazwie

-- funkcje
1. Dodaj do aplikacji funkcje edycji wiadomości.
2. Dodaj do aplikacji funkcje usuwania wiadomości.
3. Przyciski przy wiadomości ALBO na dole jest wyświelne 2 pola, jedno z usuwaniem, jedno z wyborem do edycji i
wybieramy
3.1 pola są widoczne tylko jeśli masz uprawnienia
4. pobieranie z bazy listy wszystkich ról i wyświetlania ich jako lista
5. pobieranie z bazy listy wszystkich uprawnień i wyświetlania ich jako lista pod uprawnieniami


-- notatki
1. przy tworzeniu roli wymagane jest podanie uprawnień
2. jebane chyba trzeba dodać dodawanie uprawnień poza rolą
2. "Zapisz listę uprawnień w sesji."
4. "Podczas wyświetlania strony pokazuj użytkownikowi tylko te elementy, do których ma uprawnienia."

-- do zrobienia
1. usunąć poprzednie wyswietlania edycje itd z PDO i robić nową klase z nimi

klasa privileges
funkcje:
- show all roles and privileges
- dispay messages
- display role for user
- display users
- add message
- delete message
- edit message
- create role
- delete role
- edit role


- edit user

- create privilege
- delete privilege
- edit privilege
- display privilege

pobiera login z sesji, pobiera privilegez z bazy i sprawdza czy takie privileges jest w bazie
wtedy pozwala na wykonanie funkcji

wyswietlanie tylko tych elementó do których uzytkownik ma uprawnienia
uzywamy session, funkcja jak przy sprawdzaniu uprawnień

// Plik z funkcjami (np. functions.php)
class MyClass {
public function function1() {
// Kod funkcji 1
}

public function function2() {
// Kod funkcji 2
}

public function function3() {
// Kod funkcji 3
}

// ... Dodaj pozostałe funkcje
}

// Plik, który wywołuje funkcje na podstawie uprawnień użytkownika (np. index.php)
session_start();

// Sprawdź, czy użytkownik jest zalogowany
if (isset($_SESSION['user_id'])) {
$user_id = $_SESSION['user_id'];

// Pobierz uprawnienia użytkownika z bazy danych na podstawie $user_id
// ...

// Utwórz obiekt klasy, która zawiera funkcje
$myClass = new MyClass();

// Tablica funkcji, które użytkownik może wywołać
$allowed_functions = [
'function1',
'function2',
'function3',
// ... Dodaj pozostałe funkcje
];

// Wywołaj tylko te funkcje, do których użytkownik ma uprawnienia
foreach ($allowed_functions as $function_name) {
if (in_array($function_name, $user_permissions)) {
call_user_func([$myClass, $function_name]);
}
}
} else {
// Użytkownik nie jest zalogowany - przekieruj go na stronę logowania lub wyświetl komunikat o braku dostępu
}

//Messages v2
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
                <?php echo $msg->id ?>
            </td>
            <td>
                <?php echo $msg->name ?>
            </td>
            <td>
                <?php echo $msg->message ?>
            </td>
            <form method="post" action="message_action.php">
                <input type="number" name="message_id" id="message_id" value="<?php echo $msg->id ?>" />
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

<td>Privileges</td>
<td>
    <label for="privileges"></label>"
    <?php
    $where_clause = "";
    $sql = "SELECT * FROM privilege" . $where_clause;
    $stmt = $db->pdo->prepare($sql);
    $stmt->execute();
    $privileges = $stmt->fetchAll(PDO::FETCH_OBJ);
    foreach ($privileges as $privilege) {
        echo '<input required type="radio" id="privilege" name="privilege" value="' . $privilege->id . '">' . $privilege->name . '<br>';
    }
    ?>
</td>


*/

-- uprawnienia
id name
1 add message
2 delete message
3 edit message
4 dispay message
5 create role
6 delete role
7 edit role
8 display role
9 create user
10 delete user
11 edit user
12 display user
13 create privilege
14 delete privilege
15 edit privilege
16 display privilege
17 nowe
18 nowe


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

echo '<table>';
foreach ($messages as $msg):
    ?>
    <table>
        <tr>
            <td>
                <?php echo $msg->id ?>
            </td>
            <td>
                <?php echo $msg->name ?>
            </td>
            <td>
                <?php echo $msg->message ?>
            </td>
        </tr>
    </table>

    <?php
    echo '</table>';
    //echo $msg->id . ". " . $msg->name . ". " . $msg->message . "<br>";
endforeach;

?>