<!doctype html>
<html lang="en">

<?php $title = 'Login'; ?>

<head>
    <title><?php echo htmlspecialchars($title, ENT_QUOTES | ENT_HTML5, 'utf-8'); ?></title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=yes, initial-scale=1.0, maximum-scale=5.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" href="Images/logo.webp">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" rel="stylesheet" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js" integrity="sha384-xBuQ/xzmlsLoJpyjoggmTEz8OWUFM0/RC5BsqQBDX2v5cMvDHcMakNTNrHIW2I5f" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="CSS/login.css">
</head>

<body>
    <!-- navbar -->
    <?php include 'navbar.php'; ?>
    <br>
    <!-- wrapper to show messages -->
    <?php if (isset($_GET['invalid'])) {
    ?>
        <div id="msgDiv" class="wrapper wrapper-error text-center">
            Invalid login! Please try again.
        </div>
    <?php
    } ?>
    <!-- start page content -->
    <div class="wrapper">

        <div class="logo">
            <img src="Images/logo.webp" alt="">
        </div>

        <div class="text-center mt-4 name">
            Multi-Grammar
        </div>
        <!-- login form-->
        <form class="p-3 mt-3" method="post">
            <!-- get email -->
            <div class="form-field d-flex align-items-center">
                <input type="email" name="email" id="email" placeholder="Enter email" value="<?php
                                                                                                if (isset($_GET['email']) && $_GET['email']) {
                                                                                                    echo $_GET['email'];
                                                                                                } ?>" />
            </div>
            <br> <!-- get password -->
            <div class="form-field d-flex align-items-center">
                <input type="password" name="password" id="password" pattern="[\x21-\x7E]{8,15}" placeholder="Enter password" />
            </div>
            <br>
            <div class="">
                <button type="submit" id="submitBtn" class="btn">LOGIN</button>
            </div>
        </form>

        <div class="text-center fs-6">
            Don't have an account?
            <a href="signup.php" style="font-size: medium">Sign Up</a>
        </div>
    </div>
    <script src="/scripts/login.js"></script>
</body>

</html>