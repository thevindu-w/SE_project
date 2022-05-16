<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign Up</title>
    <link rel="shortcut icon" href="Images/logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" rel="stylesheet">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link rel="stylesheet" href="CSS/signup.css">
</head>

<body>

    <!-- sign up form-->
    <div class="wrapper">

        <div class="logo">
            <img src="Images/logo.png" alt="">
        </div>

        <form class="p-3 mt-3" method="post">

            <br>
            <div class="form-field d-flex align-items-center">
                <input type="email" name="email" id="email" placeholder="Email" />
            </div>
            <br>
            <div class="form-field d-flex align-items-center">
                <input type="password" name="password" id="password" placeholder="Password" />
            </div>
            <br>
            <div class="form-field d-flex align-items-center">
                <input type="password" name="cnfpassword" id="cnfpassword" placeholder="Confirm password" />
            </div>
            <br>
            <div class="">
                <button type="submit" class="btn">SIGNUP</button>
            </div>

        </form>

        <div class="text-center fs-6">
            Already have an account?
            <a href="login.php">Sign In</a>
        </div>

    </div>
</body>

</html>