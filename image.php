<?php
require_once('utils/auth.php');
checkAuth();

require_once "vendor/autoload.php";

use thiagoalessio\TesseractOCR\TesseractOCR;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if image file is a actual image or fake image
    if (isset($_FILES["fileToUpload"]) && isset($_FILES["fileToUpload"]["tmp_name"]) && $_FILES["fileToUpload"]["tmp_name"]) {
        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
        if ($check !== false) {
            echo (new TesseractOCR($_FILES["fileToUpload"]["tmp_name"]))->run();
            /*if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                //echo "The file " . htmlspecialchars(basename($_FILES["fileToUpload"]["name"])) . " has been uploaded.";
            } else {
                echo "Sorry, there was an error uploading your file.";
            }*/
        }
    }
}
die();
?>
<html>

<body>
    <form method="post" enctype="multipart/form-data">
        Select image to upload:<br>
        <input type="file" name="fileToUpload" id="fileToUpload"><br>
        <input type="submit" value="Upload Image" name="submit">
    </form>
</body>

</html>