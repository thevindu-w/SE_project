<!doctype html>
<html lang="en">

<?php $title = 'Sign Up'; ?>

<head>
    <title><?php echo htmlspecialchars($title, ENT_QUOTES | ENT_HTML5, 'utf-8'); ?></title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" href="Images/logo.webp">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" rel="stylesheet" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js" integrity="sha384-xBuQ/xzmlsLoJpyjoggmTEz8OWUFM0/RC5BsqQBDX2v5cMvDHcMakNTNrHIW2I5f" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="CSS/signup.css">
</head>

<body>
    <!-- navbar -->
    <?php include 'navbar.php'; ?>

    <!-- wrapper to show messages -->
    <div id="msgDiv" class="wrapper text-center" hidden>
        Something went wrong! Please try again.
    </div>
    <br>
    <!-- page content -->
    <div class="wrapper">
        <div class="logo">
            <img src="Images/logo.webp" alt="">
        </div>
        <div class="text-center mt-4 name">
            Multi-Grammar
        </div>
        <!-- sign up form-->
        <form class="p-3 mt-3" method="post">
            <!-- get email -->
            <div class="form-field d-flex align-items-center">
                <input type="email" name="email" id="email" placeholder="Email" requried />
            </div>
            <br> <!-- get password -->
            <div class="form-field d-flex align-items-center">
                <input type="password" name="password" id="password" placeholder="Password" requried />
            </div>
            <br> <!-- get passsword -->
            <div class="form-field d-flex align-items-center">
                <input type="password" name="cnfpassword" id="cnfpassword" placeholder="Confirm password" requried />
            </div>
            <br>
            <div class="">
                <button id="submitBtn" type="submit" class="btn">SIGNUP</button>
            </div>
        </form>
        <div class="text-center fs-6">
            Already have an account?
            <a href="login.php" style="font-size: medium">Sign In</a>
        </div>
    </div>
    <script src="/scripts/common.js"></script>
    <script src="/scripts/signup.js"></script>
</body>

</html>