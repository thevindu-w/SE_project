<?php
require_once('utils/auth.php');
checkAuth();

require_once "vendor/autoload.php";
require_once($_SERVER['DOCUMENT_ROOT'] . '/utils/maps.php');

use thiagoalessio\TesseractOCR\TesseractOCR;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $lang = 'en-US';
    if (isset($_POST['lang']) && $_POST['lang']) {
        $lang = $_POST['lang'];
    }
    if (!in_array($lang, array_keys(LANG_OCR), true)) {
        die();
    }
    $language = 'eng';
    try {
        $language = LANG_OCR[$lang];
    } catch (Exception $e) {
    }
    
    $status = ['success' => false];
    if (isset($_FILES["fileToUpload"]) && isset($_FILES["fileToUpload"]["tmp_name"]) && $_FILES["fileToUpload"]["tmp_name"]) {
        // Check if image file is a actual image or fake image
        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
        if ($check !== false) {
            $text = (new TesseractOCR($_FILES["fileToUpload"]["tmp_name"]))->lang($language)->run();
            if ($text) {
                $text = preg_replace('/\\s+/', ' ', $text);
                $status = ['success' => true, 'text' => $text];
            }
        }
    }
    echo json_encode($status);
} else {
    header('Location: /grammar.php');
}
die();
