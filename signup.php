<?php
session_start();
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']) {
    session_write_close();
    header('Location: /grammar.php');
    die();
}
session_write_close();

require_once('utils/dbcon.php');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $status = ['success' => false];
    if (isset($_POST['email']) && isset($_POST['password']) && $_POST['email'] && $_POST['password']) {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $dbcon = DatabaseConn::get_conn();
        $token = $dbcon != null ? $dbcon->requestAccount($email, $password, 3600) : null;
        if ($token != null && $token != '0') {
            require_once 'utils/mail.php';
            $protocol = 'http://';
            if (isset($_SERVER['REQUEST_SCHEME'])){
                $protocol = $_SERVER['REQUEST_SCHEME'] . '://';
            }else if (isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) && ($_SERVER['HTTPS']=='on' || $_SERVER['HTTPS']=='off')){
                $protocol = $_SERVER['HTTPS'] === 'on' ? "https://" : "http://";
            }
            $activate_link = $protocol . "$_SERVER[HTTP_HOST]/signup.php?email=" . urlencode($email) . "&token=$token";
            $msgTemplate = file_get_contents('utils/mailTemplate.html');
            $replaceStr = ['ACTIVATE_LINK' => $activate_link];
            // the message
            $msg = strtr($msgTemplate, $replaceStr);
            $status = sendmail($email, "Account Activation", $msg, $_SERVER['DOCUMENT_ROOT'] . '/Images/logo.png');
            if ($status) {
                $status = ['success' => true];
            } else {
                $status = ['success' => false, 'reason' => 'Email sending failed'];
            }
        } else if ($token=='0') {
            $status = ['success' => false, 'reason' => 'Account already exists for this email'];
        }else {
            $status = ['success' => false, 'reason' => 'Account creation failed'];
        }
    }
    echo json_encode($status);
    die();
} else {
    if (isset($_GET['email']) && isset($_GET['token']) && $_GET['email'] && $_GET['token']) {
        $email = $_GET['email'];
        $token = $_GET['token'];
        $dbcon = DatabaseConn::get_conn();
        if ($dbcon->activateAccount($email, $token)) {
            include "views/accountActivated.php";
        } else {
            include "views/activationFailed.php";
        }
        die();
    }
    include "views/signup.php";
}
