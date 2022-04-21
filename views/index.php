<html>

<head>
    <style>
        .red {
            color: red;
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
    <form method="post">
        <div id="txtdiv" contenteditable="true" name="text">This is an sample text.<br>
            This have a grammar error.
        </div><br>
        <select id="lang" name="lang">
            <option value="en-US">English</option>
            <option value="fr-FR">French</option>
        </select><br>
        <button id="sendbtn">submit</button>
        <button id="speakbtn">speak</button><br>
    </form>
    <form method="post" enctype="multipart/form-data">
        Select image to upload:<br>
        <input type="file" name="fileToUpload" id="fileToUpload"><br>
        <button id="imgbtn">Upload Image</button>
    </form>
    <pre id="output"></pre>
    <script src="/scripts/common.js"></script>
    <script src="/scripts/script.js"></script>
</body>

</html>