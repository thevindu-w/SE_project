<?php
require_once('utils/auth.php');
checkAuth();

require_once "vendor/autoload.php";

use thiagoalessio\TesseractOCR\TesseractOCR;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $LANG_MAP = ['en-US' => 'eng', 'fr-FR' => 'fra', 'es-ES' => 'spa'];
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
        }
    }
    echo json_encode($status);
} else {
    header('Location: /index.php');
}
die();
