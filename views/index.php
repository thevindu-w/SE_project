<!doctype html>
<html lang="en">

<?php $title = 'Multi-Grammar'; ?>

<head>
    <title><?php echo $title ?></title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" href="Images/logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" rel="stylesheet" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js" integrity="sha384-xBuQ/xzmlsLoJpyjoggmTEz8OWUFM0/RC5BsqQBDX2v5cMvDHcMakNTNrHIW2I5f" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="CSS/index.css">

    <style>
        .red {
            color: red;
        }

        .green {
            color: green;
        }

        .big {
            font-size: large;
        }

        .errspan {
            font-weight: bold;
            color: #ff3f66;
        }

        #txtdiv {
            border: 1px solid #174966;
            margin: 0px;
            padding: 10px;
            width: 40%;
        }
    </style>
</head>

<body>
    <!-- navbar -->
    <?php include 'navbar.php'; ?>

    <br>
    <!-- start page content -->
    <div class="container">
        <div class="row">
            <div class="col">
                <!-- text area -->
                <form method="post">
                    <div class="txtdiv" id="txtdiv" contenteditable="true" name="text">
                        <span class="good">This is </span><span class="bad">an</span><span class="good"> sample text.<br>
                        </span><span class="bad">This have</span><span class="good"> a grammar error</span>.
                    </div>
                    <br>
                    <!-- language selection -->
                    Language:
                    <select class="dropdown" id="lang" name="lang">
                        <option value="en-US">English</option>
                        <option value="fr-FR">French</option>
                        <option value="es-ES">Spanish</option>
                    </select>
                    <button class="btn btn-blue" id="sendbtn">Submit</button>
                    <button class="btn btn-speak" id="speakbtn"><span class="material-symbols-outlined">volume_up</span></button>
                    <button class="btn btn-copy" id="copybtn"><span class="material-symbols-outlined">content_copy</span></button>
                </form>
                <br> <!-- image upload area -->
                <form method="post" enctype="multipart/form-data">
                    Select image to extract text:
                    <input class="upload" type="file" name="fileToUpload" id="fileToUpload">
                    <br>
                    <button class="btn btn-img" id="imgbtn">Upload Image</button>
                </form>
                <br>
            </div>
            <!-- show errors -->
            <div class="col">
                <div id="errors">
                </div>
            </div>
        </div>
    </div>

    <script src="/scripts/common.js"></script>
    <script src="/scripts/script.js"></script>
</body>

</html>