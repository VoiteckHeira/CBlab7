<?php
require './htmlpurifier-4.15.0/library/HTMLPurifier.auto.php';
require 'Aes.php';
require './email.php';


class Pdo_
{
    private $db;
    private $purifier;

    private $aes;
    private $log_2F_step1;
    //private $pepper = '2a07e8e4ee40cfc34d4ad41ce2f21419';
    private $mail;

    public function __construct()
    {
        $config = HTMLPurifier_Config::createDefault();
        $this->purifier = new HTMLPurifier($config);
        try {
            $this->db = new PDO('mysql:host=localhost;dbname=news', 'root', '');

            $this->mail = new M();

        } catch (PDOException $e) {
            die();
        }
    }

    public function add_user($login, $email, $password, $password2, $role_name, $twofa)
    {

        $salt = random_bytes(16);
        $pepper = '2a07e8e4ee40cfc34d4ad41ce2f21419';

        $this->aes = new Aes($password);


        $login = $this->purifier->purify($login);
        $email = $this->purifier->purify($email);



        try {
            $sql = "INSERT INTO 
                    `user`( `login`, `email`, `hash`, `salt`, `id_status`, `password_form`, `2fa`) 
                VALUES 
                    (:login,:email,:hash,:salt,:id_status,:password_form,:2fa)";

            $password = hash('sha512', $password . $salt . $pepper, false);

            $encrypted_password = $this->aes->encrypt($password);
            $data = [
                'login' => $login,
                'email' => $email,
                'hash' => $encrypted_password,
                'salt' => $salt,
                'id_status' => '1',
                'password_form' => '1',
                '2fa' => (int) $twofa,
            ];
            $this->db->prepare($sql)->execute($data);
            $id_user = $this->db->lastInsertId(); // get the last inserted id
            // echo $id_role;
            $id_role = $role_name;

            $sql = "INSERT INTO user_role(id_user, id_role, issue_time, expire_time) 
                VALUES (:id_user, :id_role, :issue_time, :expire_time)";
            $data = [
                'id_user' => $id_user,
                'id_role' => $id_role,
                'issue_time' => date('Y-m-d'),
                'expire_time' => null
            ];
            $this->db->prepare($sql)->execute($data);

            echo ' <br> user added';



            // Dodaj uprawnienia dla użytkownika w tabeli user_privilege
            $sql = "INSERT INTO user_privilege(id_user, id_privilege) 
                SELECT :id_user, privilege_id 
                FROM role_privilege 
                WHERE id_role = :id_role";
            $data = [
                'id_user' => $id_user,
                'id_role' => $id_role
            ];
            $this->db->prepare($sql)->execute($data);

        } catch (Exception $e) {

            print 'Exception' . $e->getMessage();
        }
    }
    public function log_user_in($login, $password)
    {
        $login = $this->purifier->purify($login);
        $login = addslashes($login);
        $pepper = '2a07e8e4ee40cfc34d4ad41ce2f21419';
        $this->aes = new Aes($password);
        try {
            $sql = "SELECT id,hash,login,salt FROM user WHERE login=:login";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['login' => $login]);
            $user_data = $stmt->fetch();
            $salt = $user_data['salt'];


            if (empty($user_data)) {
                echo 'There is no user with login ' . $login;
                return false;
            }


            $decrypted_password = $this->aes->decrypt($user_data['hash']);
            $password = hash('sha512', $password . $salt . $pepper, false);


            if ($password == $decrypted_password) {
                echo 'login successfull<BR/>';
                echo 'You are logged in as: ' . $user_data['login'] . '<BR/>';

                $_SESSION['login'] = $user_data['login'];
                $_SESSION['session_expire'] = time();

            } else {
                echo 'login FAILED<BR/>';
            }
        } catch (Exception $e) {

            print 'Exception' . $e->getMessage();
        }
    }

    public function change_password($old_password, $password, $password2)
    {
        try {
            $login = $_SESSION['login'];
            $pepper = '2a07e8e4ee40cfc34d4ad41ce2f21419';
            $this->aes = new Aes($old_password);

            $sql = "SELECT id,hash,login,salt FROM user WHERE login=:login";

            $stmt = $this->db->prepare($sql);
            $stmt->execute(['login' => addslashes($login)]);
            $user_data = $stmt->fetch();
            $salt = $user_data['salt'];

            $decrypted_password = $this->aes->decrypt($user_data['hash']);
            $old_password = hash('sha512', $old_password . $salt . $pepper, false);

            if ($old_password != $decrypted_password) {
                echo 'Wrong Password <BR/>';
                return false;
            } elseif ($password != $password2) {
                echo 'Passwords are not same';
                return false;
            } else {
                try {

                    $login = $_SESSION['login'];
                    $pepper = '2a07e8e4ee40cfc34d4ad41ce2f21419';
                    $salt = random_bytes(16);
                    $this->aes = new Aes($password);

                    $hash = hash('sha512', $password . $salt . $pepper, false);

                    $encrypted_password = $this->aes->encrypt($hash);
                    $sql = "UPDATE user SET hash=:hash, salt=:salt WHERE login=:login";

                    $data = [
                        'login' => $login,
                        'hash' => $encrypted_password,
                        'salt' => $salt,
                    ];

                    $this->db->prepare($sql)->execute($data);

                    echo 'Password changed';
                    unset($_SESSION['login']);
                } catch (Exception $e) {
                    //modify the code here
                    print 'Exception' . $e->getMessage();
                }
            }
        } catch (Exception $e) {
            print 'Exception' . $e->getMessage();
        }
    }

    public function log_2F_step1($login, $password)
    {
        $login = $this->purifier->purify($login);

        try {
            $pepper = '2a07e8e4ee40cfc34d4ad41ce2f21419';
            $sql = "SELECT id,hash,login,salt,email,2fa FROM user WHERE login=:login";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['login' => $login]);
            $user_data = $stmt->fetch();

            $this->aes = new Aes($password);
            $salt = $user_data['salt'];

            $decrypted_password = $this->aes->decrypt($user_data['hash']);
            $password = hash('sha512', $password . $salt . $pepper, false);

            if (!$user_data['2fa']) {


                if ($password == $decrypted_password) {
                    $result = [
                        'result' => 'logged_in'
                    ];

                    return $result;
                } else {

                    $result = [
                        'result' => 'failed'
                    ];
                    echo 'login FAILED<BR/>';
                    return $result;
                }
            }

            if ($password == $decrypted_password) {


                //generate and send OTP
                $otp = random_int(100000, 999999);
                $code_lifetime = date('Y-m-d H:i:s', time() + 300);
                try {
                    $sql = "UPDATE `user` SET `sms_code`=:code, `code_timelife`=:lifetime WHERE login=:login";
                    $data = [
                        'login' => $login,
                        'code' => $otp,
                        'lifetime' => $code_lifetime
                    ];
                    $this->db->prepare($sql)->execute($data);

                    $this->mail->send_email($user_data['email'], $otp);

                    //add the code to send an e-mail with OTP
                    $result = [
                        'result' => 'success'
                    ];
                    return $result;
                } catch (Exception $e) {
                    print 'Exception' . $e->getMessage();
                    //add necessary code here
                }
            } else {
                echo 'login FAILED<BR/>';
                $result = [
                    'result' => 'failed'
                ];
                echo 'login FAILED<BR/>';
                sleep(10);

                // Przekierowanie do innej strony
                header("Location: index.php");
                return $result;
            }
        } catch (Exception $e) {
            print 'Exception' . $e->getMessage();
            //add necessary code here
        }
    }

    public function log_2F_step2($login, $code)
    {
        $login = $this->purifier->purify($login);
        $code = $this->purifier->purify($code);
        try {
            $sql = "SELECT id,login,sms_code,code_timelife FROM user WHERE login=:login";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['login' => $login]);
            $user_data = $stmt->fetch();
            if (
                $code == $user_data['sms_code'] && time() < strtotime($user_data['code_timelife'])
            ) {
                //login successfull
                echo 'Login successfull<BR/>';

                $_SESSION['login'] = $user_data['login'];
                $_SESSION['session_expire'] = time();
                return true;
            } else {
                echo 'login FAILED<BR/>';
                echo 'login FAILED<BR/>';
                sleep(10);

                // Przekierowanie do innej strony
                header("Location: index.php");
                return false;

            }
        } catch (Exception $e) {
            print 'Exception' . $e->getMessage();
        }
    }


    public function get_role($login)
    {
        $login = $this->purifier->purify($login);

        try {
            $sql = "SELECT r.role_name FROM role r"
                . " INNER JOIN user_role ur ON ur.id_role = r.id"
                . " INNER JOIN user u ON u.id = ur.id_user"
                . " WHERE u.login = :login";

            $stmt = $this->db->prepare($sql);
            $stmt->execute(['login' => $login]);
            $role = $stmt->fetchColumn();
            if ($role) {
                echo "Rola: " . $role . "<br/>";
            } else {
                echo "Brak przypisanej roli dla użytkownika<br/>";
            }
        } catch (Exception $e) {
            print 'Exception' . $e->getMessage();
            echo 'Exception' . $e->getMessage();
        }
    }
    public function get_privileges($login)
    {
        $login = $this->purifier->purify($login);

        try {
            $sql = "SELECT p.name FROM privilege p"
                . " INNER JOIN role_privilege rp ON rp.privilege_id = p.id"
                . " INNER JOIN role r ON r.id = rp.id_role"
                . " INNER JOIN user_role ur ON ur.id_role = r.id"
                . " INNER JOIN user u ON u.id = ur.id_user"
                . " WHERE u.login = :login";

            $stmt = $this->db->prepare($sql);
            $stmt->execute(['login' => $login]);
            $privileges = $stmt->fetchAll();
            if ($privileges) {
                echo "Przywilej: " . "<br/>";
                foreach ($privileges as $privilege) {
                    echo "- " . $privilege['name'] . "<br/>";
                }
            } else {
                echo "Brak przypisanych przywilejów dla użytkownika<br/>";
            }
        } catch (Exception $e) {
            print 'Exception' . $e->getMessage();
            echo 'Exception' . $e->getMessage();
        }
    }

    public function get_roles_with_privileges($login)
    {
        $login = $this->purifier->purify($login);
        try {
            $sql = "SELECT r.role_name, p.name FROM role r"
                . " INNER JOIN role_privilege rp ON r.id = rp.id_role"
                . " INNER JOIN privilege p ON p.id = rp.privilege_id"
                . " INNER JOIN user_role ur ON ur.id_role = r.id"
                . " INNER JOIN user u ON u.id = ur.id_user"
                . " WHERE u.login = :login";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['login' => $login]);
            $data = $stmt->fetchAll();

            foreach ($data as $row) {
                $role = $row['role_name'];
                $privilege = $row['name'];
                $_SESSION[$role][$privilege] = 'YES';
            }

            $data['status'] = 'success';
            echo 'Roles and privileges set<br/>';
            return $data;
        } catch (Exception $e) {
            print 'Exception' . $e->getMessage();
            echo 'Exception' . $e->getMessage();
        }

        return [
            'status' => 'failed'
        ];
    }

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



} // END OF FILE