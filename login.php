<?php
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['logout'])) {
    session_start();
    $_SESSION['logged_in'] = false;
    $_SESSION['target'] = null;
    session_write_close();
    header('Location: /login.php');
    die();
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
            $target = '/index.php';
            if (isset($_SESSION['target']) && $_SESSION['target'] != null) {
                $target = $_SESSION['target'];
            }
            $_SESSION['target'] = null;
            session_write_close();
            header('Location: ' . $target);
            die();
        }
    }
    header('Location: /login.php');
    die();
} else {
?>
    <html>

    <body>
        <form method="post">
            Email :
            <input type="email" name="email" value="<?php if (isset($_GET['email']) && $_GET['email']) {
                                                        echo $_GET['email'];
                                                    } ?>" /><br>
            Password :
            <input type="password" name="password" /><br>
            <button type="submit">Login</button>
        </form>
        <p>Don't have an account? <a href="/signup.php">Signup</a></p>
    </body>

    </html>
<?php
}
?>