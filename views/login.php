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