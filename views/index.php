<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="Images/logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" rel="stylesheet">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <title>Index</title>
    <link rel="stylesheet" href="CSS/index.css">

    <style>
        .red {
            color: red;
        }

        .big{
            font-size: large;
        }

        #txtdiv {
            border: 1px solid black;
            margin: 5px;
            padding: 5px;
            width: 40%;
        }
    </style>
</head>

<body>
    <div class="container">
        <form method="post">
            <div class="txtdiv" id="txtdiv" contenteditable="true" name="text">This is an sample text.<br>
                This have a grammar error.
            </div>
            Language:
            <select class="dropdown" id="lang" name="lang">
                <option value="en-US">English</option>
                <option value="fr-FR">French</option>
            </select>
            <button class="btn btn-blue" id="sendbtn">submit</button>
            <button class="btn btn-blue" id="speakbtn">speak</button><br>
        </form>
        <br>
        <div id="errors"></div>
        <form method="post" enctype="multipart/form-data">
            Select image to upload:<br>
            <input class="upload" type="file" name="fileToUpload" id="fileToUpload">
            <button class="btn btn-img" id="imgbtn">Upload Image</button>
        </form>
        <pre id="output"></pre>
        <script src="/scripts/common.js"></script>
        <script src="/scripts/script.js"></script>
    </div>
    
</body>

</html>