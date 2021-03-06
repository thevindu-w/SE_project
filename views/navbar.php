<?php
session_start();
$isLogged = isset($_SESSION['logged_in']) && $_SESSION['logged_in'];
?>

<!-- start navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light" style="box-shadow: 13px 13px 20px #cbced1;">
    <div class="container-fluid">
        <!-- navbar brand -->
        <a class="navbar-brand" href="index.php" style="padding: 2px 2px; font-weight: bold; font-size: x-large; color: #174966; font-family: Helvetica, sans-serif;">
            <img src="Images/logo.webp" alt="Avatar Logo" style="width:35px; height: 35px; object-fit: cover; margin: 1px; border-radius: 50%; box-shadow: 0px 0px 3px #5f5f5f, 0px 0px 0px 5px #ecf0f3, 8px 8px 15px #a7aaa7, -8px -8px 15px #fff;" class="rounded-pill"> &nbsp;&nbsp;Multi-Grammar
        </a>
        <button class="navbar-toggler collapsed d-flex d-lg-none flex-column justify-content-around" type="button" data-bs-toggle="collapse" data-bs-target="#navbarcontent" aria-controls="navbarcontent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="toggler-icon top-bar"></span>
            <span class="toggler-icon middle-bar"></span>
            <span class="toggler-icon bottom-bar"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarcontent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <!-- button to index page -->
                <?php if ($isLogged) {
                    if ($title == "Home") { ?>
                        <li class="nav-item name-link" align="center" style="font-size: large; font-weight: bold; color: #174966; font-family: Helvetica, sans-serif;">
                            <a class="nav-link" href="grammar.php">Check Grammar</a>
                        </li>
                <?php }
                } ?>
            </ul>
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <!-- button login -->
                <?php if (!$isLogged) {
                    if ($title == "Sign Up" || $title == "Activation" || $title == "Home") { ?>
                        <li class="nav-item" align="center"><a class="btn btn-blue" aria-current="page" href="login.php">Login</a></li>
                <?php }
                }
                // button signup
                if (!$isLogged) {
                    if ($title == "Login" || $title == "Activation" || $title == "Home") { ?>
                        <li class="nav-item" align="center"><a class="btn btn-blue" aria-current="page" href="signup.php">Signup</a></li>
                <?php }
                }
                // button logout
                if ($isLogged) {
                    if ($title == "Multi-Grammar" || $title == "Home") { ?>
                        <li class="nav-item" align="center"><a class="btn btn-black" aria-current="page" href="/login.php?logout=1">Logout</a></li>
                <?php }
                } ?>
            </ul>
        </div>
    </div>
</nav>
<!-- end navbar -->