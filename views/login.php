<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" href="Images/logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" rel="stylesheet" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js" integrity="sha384-xBuQ/xzmlsLoJpyjoggmTEz8OWUFM0/RC5BsqQBDX2v5cMvDHcMakNTNrHIW2I5f" crossorigin="anonymous"></script>
    <title>Login</title>
    <link rel="stylesheet" href="CSS/login.css">
</head>

<body>
    <!-- start navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light" style="box-shadow: 13px 13px 20px #cbced1;">
        <div class="container-fluid">
            <!-- navbar brand -->
            <a class="navbar-brand" href="#" style="padding: 2px 2px; font-weight: bold; font-size: x-large; color: #174966; font-family: 'Roboto Slab', serif;">
                <img src="Images/logo.png" alt="Avatar Logo" style="width:35px; object-fit: cover; margin: 1px; border-radius: 50%; box-shadow: 0px 0px 3px #5f5f5f, 0px 0px 0px 5px #ecf0f3, 8px 8px 15px #a7aaa7, -8px -8px 15px #fff;" class="rounded-pill"> &nbsp;&nbsp;Multi-Grammar
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <!-- button signup -->
                    <li class="nav-item">
                        <a class="btn btn-blue" aria-current="page" href="signup.php">Signup</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- end navbar -->
    <br>
    <!-- start page content -->
    <div class="wrapper">

        <div class="logo">
            <img src="Images/logo.png" alt="">
        </div>

        <div class="text-center mt-4 name">
            Multi-Grammar
        </div>
        <!-- login form-->
        <form class="p-3 mt-3" method="post">
            <div class="form-field d-flex align-items-center">
                <input type="email" name="email" id="email" placeholder="Enter email" value="<?php
                            if (isset($_GET['email']) && $_GET['email']) { echo $_GET['email']; } ?>" />
            </div>
            <br>
            <div class="form-field d-flex align-items-center">
                <input type="password" name="password" id="password" placeholder="Enter password" />
            </div>
            <br>
            <div class="">
                <button type="submit" class="btn">LOGIN</button>
            </div>

        </form>

        <div class="text-center fs-6">
            Don't have an account?
            <a href="signup.php" style="font-size: medium">Sign Up</a>
        </div>
    </div>
</body>

</html>