<?php
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['logout'])) {
    session_start();
    $_SESSION['logged_in'] = false;
    session_write_close();
}
session_start();
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']) {
    session_write_close();
    header('Location: /index.php');
    die();
}
session_write_close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once('utils/dbcon.php');
    if (isset($_POST['email']) && isset($_POST['password']) && $_POST['email'] && $_POST['password']) {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $dbcon = DatabaseConn::get_conn();
        if ($dbcon->auth($email, $password)) {
            session_start();
            $_SESSION['logged_in'] = true;
            session_write_close();
            header('Location: /index.php');
        } else {
            header('Location: /login.php');
        }
    }
    die();
} else {
?>
    <html>

    <body>
        <form method="post">
            Email :
            <input type="email" name="email" /><br>
            Password :
            <input type="password" name="password" /><br>
            <button type="submit">Login</button>
        </form>
    </body>

    </html>
<?php
}
?>