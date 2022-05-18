<?php
require_once('utils/auth.php');
checkAuth();

require_once "vendor/autoload.php";

use thiagoalessio\TesseractOCR\TesseractOCR;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $LANG_MAP = ['en-US' => 'eng', 'fr-FR' => 'fra'];
    $status = ['success' => false];
    $lang = 'en-US';
    if (isset($_POST['lang']) && $_POST['lang']) {
        $lang = $_POST['lang'];
    }
    $language = 'eng';
    try {
        $language = $LANG_MAP[$lang];
    } catch (Exception $e) {
    }
    if (isset($_FILES["fileToUpload"]) && isset($_FILES["fileToUpload"]["tmp_name"]) && $_FILES["fileToUpload"]["tmp_name"]) {
        // Check if image file is a actual image or fake image
        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
        if ($check !== false) {
            $text = (new TesseractOCR($_FILES["fileToUpload"]["tmp_name"]))->lang($language)->run();
            if ($text) {
                $status = ['success' => true, 'text' => $text];
            }
            /*if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                //echo "The file " . htmlspecialchars(basename($_FILES["fileToUpload"]["name"])) . " has been uploaded.";
            } else {
                echo "Sorry, there was an error uploading your file.";
            }*/
        }
    }
    echo json_encode($status);
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