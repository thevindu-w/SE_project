<!doctype html>
<html lang="en">

<?php $title = 'Home'; ?>

<head>
    <title><?php echo $title ?></title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" href="Images/logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" rel="stylesheet" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,1,0" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js" integrity="sha384-xBuQ/xzmlsLoJpyjoggmTEz8OWUFM0/RC5BsqQBDX2v5cMvDHcMakNTNrHIW2I5f" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="CSS/index.css">
</head>

<body>
    <!-- navbar -->
    <?php include 'navbar.php'; ?>

    <br>
    <!-- start page content -->
    <div class="container">
        <div class="row" style="margin-top: 15px;">
            <div class="col-md">
                <div class="description" style="margin-top: 20px">
                    <h1 class="text-center title" style="margin: 0px auto; color: #0078b4">
                        Welcome <br> to Multi-Grammar
                    </h1>
                    <div class="text-center" style="margin: auto; font-family: Inter, sans-serif;">
                        Your most reliable multi-language grammar checker.
                    </div>
                </div>
            </div>

            <div class="col-md">
                <div class="wrapper">
                    <h1 class="title" style="font-size: 18px">How to use:</h1>
                    <ul style="font-size: 14px; font-family: Inter, sans-serif;">
                        <li>
                            Select the language.
                        </li>
                        <li>
                            Add the text you want to check in the text box given.
                        </li>
                        <li>
                            Click on the submit button.
                        </li>
                        <li>
                            Grammatically wrong words are displayed in red.
                        </li>
                        <li>
                            Select a suggested words which are given in a drop-down box on the right showing the required grammar requirement for the correct sentence.
                        </li>
                    </ul>

                    <h1 class="title" style="font-size: 18px">If you want to enter a image:</h1>
                    <ul style="font-size: 14px; font-family: Inter, sans-serif;">
                        <li>
                            Select the language.
                        </li>
                        <li>
                            Select the image from your device using the browse button and click upload button.
                        </li>
                        <li>
                            A text version of your image will be extracted and displayed on the text box.
                        </li>
                        <li>
                            Continue with the above given steps.
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <br>

        <div class="row" style="margin-top: 15px;">
            <dev class="col">
                <h1 class="title" style="margin-left: 10px; font-size: 18px">Additional Features:</h1>
                <ul style="margin-left: 10px; margin-right: 10px; font-size: 14px; font-family: Inter, sans-serif;">
                    <li>
                        Text to Speach -
                        <p>When pressed on the Speaker button below the text box when pressed, the entered text in the text box will be read aloud.</p>
                    </li>
                    <li>
                        Copy to Clipboard -
                        <p>When pressed on the Speaker button below the text box when pressed, the entered text in the text box will be read aloud.</p>
                    </li>
                    <li>
                        Download -
                        <p>Download</p>
                    </li>
                </ul>
            </dev>
        </div>
    </div>

</body>

</html>