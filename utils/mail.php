<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require $_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php';

function sendmail(string $email, string $subject, string $message): bool
{
    $mail_config = parse_ini_file('.env');
    $sender = $mail_config['MAIL_SENDER'];
    $password = $mail_config['MAIL_PASSWORD'];
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->SMTPDebug = SMTP::DEBUG_OFF; //Disable verbose debug output
        $mail->isSMTP();                    //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';  //Set the SMTP server to send through
        $mail->SMTPAuth   = true;              //Enable SMTP authentication
        $mail->Username   = $sender;           //SMTP username
        $mail->Password   = $password;         //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;  //Enable implicit TLS encryption
        $mail->Port       = 587;                             //TCP port to connect to

        //Recipients
        $mail->setFrom($sender, "Multi-Grammar");
        $mail->addAddress($email);               //Name is optional

        //Content
        $mail->isHTML(true);   //Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body    = $message;
        $mail->AltBody = $message;

        return $mail->send();
    } catch (Exception $e) {
        return false;
    }
}