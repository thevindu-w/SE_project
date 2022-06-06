<?php
/**
 * Checks if the user is logged in. If the user is logged in,
 * this function does nothing. If the user is not logged in,
 * redirects the user to login.
 * @return void
 */
function checkAuth()
{
    session_start();
    if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
        $_SESSION['target'] = $_SERVER['REQUEST_URI'];
        session_write_close();
        header('Location: /login.php');
        die();
    }else{
        $_SESSION['target'] = null;
        session_write_close();
    }
}
