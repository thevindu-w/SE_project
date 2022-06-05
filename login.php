<?php
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['logout'])) {
    session_start();
    $_SESSION['logged_in'] = false;
    $_SESSION['target'] = null;
    session_write_close();
    header('Location: /index.php');
    die();
}
session_start();
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']) {
    session_write_close();
    header('Location: /grammar.php');
    die();
}
session_write_close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once('utils/databaseConnection.php');
    if (isset($_POST['email']) && isset($_POST['password']) && $_POST['email'] && $_POST['password']) {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $dbConnection = DatabaseConnection::getConnection();
        if ($dbConnection != null && $dbConnection->auth($email, $password)) {
            session_start();
            $_SESSION['logged_in'] = true;
            $target = '/grammar.php';
            if (isset($_SESSION['target']) && $_SESSION['target'] != null) {
                $target = $_SESSION['target'];
            }
            $_SESSION['target'] = null;
            session_write_close();
            header('Location: ' . $target);
            die();
        }else{
            header('Location: /login.php?invalid');
            die();
        }
    }
    header('Location: /login.php');
    die();
} else {
    header('Unauthorized: /login.php');
    include "views/login.php";
}
