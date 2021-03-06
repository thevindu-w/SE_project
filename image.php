<?php
require_once('utils/auth.php');
checkAuth();

require_once "vendor/autoload.php";
require_once($_SERVER['DOCUMENT_ROOT'] . '/utils/languages.php');

use thiagoalessio\TesseractOCR\TesseractOCR;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $status = ['success' => false];
    $lang = 'en-US';
    if (isset($_POST['lang']) && $_POST['lang']) {
        $lang = $_POST['lang'];
    }
    if (!in_array($lang, array_keys(LANG_OCR), true)) {
        $status['reason'] = 'Language not supported';
        echo json_encode($status);
        die();
    }
    $language = 'eng';
    try {
        $language = LANG_OCR[$lang];
    } catch (Exception $e) {
    }
    
    if (isset($_FILES["fileToUpload"]) && isset($_FILES["fileToUpload"]["tmp_name"]) && $_FILES["fileToUpload"]["tmp_name"]) {
        // Check if image file is a actual image or fake image
        $checkImage = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
        if ($checkImage !== false) {
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
