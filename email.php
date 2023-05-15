<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require './PHPMailer/src/Exception.php';
require './PHPMailer/src/PHPMailer.php';
require './PHPMailer/src/SMTP.php';


// $m = new M();
// $m->send_email('s95390@pollub.edu.pl', 'xd');

// $mail = new PHPMailer(true);

// try {
//     //Server settings
//     $mail->SMTPDebug = SMTP::DEBUG_SERVER; //Enable verbose debug output
//     $mail->isSMTP(); //Send using SMTP
//     $mail->Host = 'poczta.o2.pl'; //Set the SMTP server to send through
//     $mail->SMTPAuth = true; //Enable SMTP authentication
//     $mail->Username = 's95390@o2.pl'; //SMTP username
//     $mail->Password = 'Nezumi20'; //SMTP password
//     $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; //Enable implicit TLS encryption
//     $mail->Port = 465; //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

//     //Recipients
//     $mail->setFrom('from@example.com', 'Mailer');
//     $mail->addAddress('s95390@pollub.edu.pl', 'Joe User'); //Add a recipient


//     //Attachments
//     // $mail->addAttachment('/var/tmp/file.tar.gz'); //Add attachments
//     // $mail->addAttachment('/tmp/image.jpg', 'new.jpg'); //Optional name

//     //Content
//     $mail->isHTML(true); //Set email format to HTML
//     $mail->Subject = 'Here is the subject';
//     $mail->Body = 'This is the HTML message body <b>in bold!</b>';
//     $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

//     $mail->send();
//     echo 'Message has been sent';
// } catch (Exception $e) {
//     echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
// }

class M
{
    public function send_email($address, $content)
    {
        $mail = new PHPMailer(true);

        try {
            // Serwer SMTP Mailtrap
            // $mail->SMTPDebug = 2;
            $mail->isSMTP();
            $mail->Host = 'smtp.mailtrap.io';
            $mail->SMTPAuth = true;
            $mail->Username = 'b3551d02d9d42d'; // login z Mailtrap
            $mail->Password = '49bd670b97d104'; // hasło z Mailtrap
            $mail->SMTPSecure = 'tls';
            $mail->Port = 2525;

            // Nadawca i odbiorca
            $mail->setFrom('nadawca@example.com', 'Nadawca');
            $mail->addAddress($address, 'Odbiorca');

            // Treść e-maila
            $mail->isHTML(true);
            $mail->Subject = 'Kod do logowania';
            $mail->Body = $content;

            $mail->send();
            echo 'Wiadomość została wysłana';
        } catch (Exception $e) {
            echo "Nie udało się wysłać wiadomości. Błąd: {$mail->ErrorInfo}";
        }
    }
}