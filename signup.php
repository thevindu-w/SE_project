<?php
require_once('utils/dbcon.php');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $status = ['success' => false];
    if (isset($_POST['email']) && isset($_POST['password']) && $_POST['email'] && $_POST['password']) {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $dbcon = DatabaseConn::get_conn();
        $token = $dbcon->requestAccount($email, $password, 3600);
        if ($token != null) {
            $activate_link = "$_SERVER[HTTP_HOST]/signup.php?email=" . urlencode($email) . "&token=$token";
            $headers  = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
            // the message
            $msg = "<html><body><h1>Account Activation</h1><p>Click <a href=\"$activate_link\">this link</a> to activate your account.</p><a href=\"\"></body></html>";
            $status = mail($email, "Account Activation", $msg, $headers);
            if ($status) {
                $status = ['success' => true];
            } else {
                $status = ['success' => false, 'reason' => 'Email sending failed'];
            }
        } else {
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
?>