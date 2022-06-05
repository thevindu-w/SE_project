<!doctype html>
<html lang="en">

<?php $title = 'Multi-Grammar'; ?>

<head>
    <title><?php echo htmlspecialchars($title, ENT_QUOTES | ENT_HTML5, 'utf-8'); ?></title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" href="Images/logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" rel="stylesheet" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    <!--Cannot use resource integrity check for this since its content depends on the browser-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js" integrity="sha384-xBuQ/xzmlsLoJpyjoggmTEz8OWUFM0/RC5BsqQBDX2v5cMvDHcMakNTNrHIW2I5f" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="CSS/grammar.css">
</head>

<body>
    <!-- navbar -->
    <?php include 'navbar.php'; ?>

    <br>
    <!-- start page content -->
    <div class="container">
        <div class="row">
            <div class="col-md">
                <!-- text area -->
                <form method="post">
                    <div class="txtdiv">
                        <div id="txtdiv" contenteditable="true" name="text">
                            <span class="good">This is </span><span class="bad">an</span><span class="good"> sample text.<br>
                            </span><span class="bad">This have</span><span class="good"> a grammar error</span>.
                        </div>
                        <div class="button-icon" style="position: absolute; bottom:3px; right:3px;">
                            <div class="popup"><button class="btn btn-speak" title="download" id="downbtn"><span class="material-symbols-outlined">download</span></button>
                                <span class="popuptext" id="myPopup">
                                    <button id="textbtn" class="btn btn-blue btn-down">txt</button>
                                    <button id="pdfbtn" class="btn btn-blue btn-down">pdf</button>
                                </span>
                            </div>
                            <button class="btn btn-speak" title="speak" id="speakbtn"><span class="material-symbols-outlined">volume_up</span></button>
                            <button class="btn btn-stop" title="stop" id="stopbtn" hidden><span class="material-symbols-outlined">stop</span></button>
                            <button class="btn btn-copy" title="copy to clipboard" id="copybtn"><span class="material-symbols-outlined">content_copy</span></button>
                        </div>
                    </div>
                    <br>
                    <!-- image upload area -->
                    Select image to extract text:
                    <form method="post" enctype="multipart/form-data">
                        <input class="up-input" id="fileToUpload" type="file" name="fileToUpload" accept="image/*"><br>
                    </form>
                    <!-- language selection -->
                    Language:
                    <select class="dropdown" id="lang" name="lang">
                        <?php
                        require_once($_SERVER['DOCUMENT_ROOT'] . '/utils/languages.php');
                        foreach (LANG_VIEW as $lang => $name) { ?>
                            <option value="<?php echo htmlspecialchars($lang, ENT_QUOTES, 'utf-8'); ?>"><?php echo htmlspecialchars($name, ENT_QUOTES | ENT_HTML5, 'utf-8'); ?></option>
                        <?php
                        }
                        ?>
                    </select>
                    <button class="btn btn-blue" id="imgbtn">Upload</button>
                    <button class="btn btn-blue" id="sendbtn">Check</button>
                </form>

            </div>
            <!-- show errors -->
            <div class="col-md">
                <div id="msgDiv" class="wrapper wrapper-success text-center" hidden>
                    No grammar errors.
                </div>
                <div id="errors">
                </div>
            </div>
        </div>
    </div>

    <script src="/scripts/common.js"></script>
    <script src="/scripts/grammar.js"></script>
</body>

</html>