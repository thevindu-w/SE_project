<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
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
            border: 1px solid black;
            margin: 5px;
            padding: 5px;
            width: 40%;
        }
    </style>
</head>

<body>
    <!-- Navigation Bar -->

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#" style="font-weight: bold; font-size: x-large; color: #174966; font-family: 'Roboto Slab', serif;">
                <img src="Images/logo.png" alt="Avatar Logo" style="width:35px;" class="rounded-pill"> Multi-Grammar
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="/login.php?logout=1" style="font-size: large;">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <br>

    <div class="container">
        <div class="row">
            <div class="col">
                <form method="post">
                    <div class="txtdiv" id="txtdiv" contenteditable="true" name="text">
                        <span class="good">This is </span><span class="bad">an</span><span class="good"> sample text.<br>
                        </span><span class="bad">This have</span><span class="good"> a grammar error</span>.
                    </div>
                    <br>
                    Language:
                    <select class="dropdown" id="lang" name="lang">
                        <option value="en-US">English</option>
                        <option value="fr-FR">French</option>
                    </select>
                    <button class="btn btn-blue" id="sendbtn">submit</button>
                    <button class="btn btn-blue" id="speakbtn">speak</button>
                </form>
                <br>
                <br>
                <form method="post" enctype="multipart/form-data">
                    Select image to extract text:
                    <input class="upload" type="file" name="fileToUpload" id="fileToUpload">
                    <br>
                    <button class="btn btn-img" id="imgbtn">Upload Image</button>
                </form>
            </div>

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