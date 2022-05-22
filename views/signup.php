<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" href="Images/logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" rel="stylesheet" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js" integrity="sha384-xBuQ/xzmlsLoJpyjoggmTEz8OWUFM0/RC5BsqQBDX2v5cMvDHcMakNTNrHIW2I5f" crossorigin="anonymous"></script>
    <title>Sign Up</title>
    <link rel="stylesheet" href="CSS/signup.css">
</head>

<body>
    <!-- Navigation Bar -->

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#" style="font-weight: bold; font-size: x-large; color: #174966; font-family: 'Roboto Slab', serif;">
                <img src="Images/logo.png" alt="Avatar Logo" style="width:35px; object-fit: cover; margin: 1px; border-radius: 50%; box-shadow: 0px 0px 3px #5f5f5f, 0px 0px 0px 5px #ecf0f3, 8px 8px 15px #a7aaa7, -8px -8px 15px #fff;" class="rounded-pill"> Multi-Grammar
            </a>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="btn btn-blue" aria-current="page" href="login.php">Login</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- sign up form-->
    <div class="wrapper">

        <div class="logo">
            <img src="Images/logo.png" alt="">
        </div>

        <div class="text-center mt-4 name">
            Multi-Grammar
        </div>

        <form class="p-3 mt-3" method="post">

            <div class="form-field d-flex align-items-center">
                <input type="email" name="email" id="email" onkeypress="keyPressFn(event, 'password')" placeholder="Email" />
            </div>
            <br>
            <div class="form-field d-flex align-items-center">
                <input type="password" name="password" id="password" onkeypress="keyPressFn(event, 'cnfpassword')" placeholder="Password" />
            </div>
            <br>
            <div class="form-field d-flex align-items-center">
                <input type="password" name="cnfpassword" id="cnfpassword" onkeypress="keyPressFn(event, '')" placeholder="Confirm password" />
            </div>
            <br>
            <div class="">
                <button id="submitBtn" type="submit" class="btn">SIGNUP</button>
            </div>

        </form>

        <div class="text-center fs-6">
            Already have an account?
            <a href="login.php">Sign In</a>
        </div>

    </div>
    <script src="/scripts/signup.js"></script>
    <script src="/scripts/common.js"></script>
</body>

</html>