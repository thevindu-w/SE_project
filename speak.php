<?php
require_once('utils/auth.php');
checkAuth();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $text = $_POST['text'];
    $lang = $_POST['lang'];

    // echo json_encode(["text" => $text]);
    // die();
    $fname = "tmp/audio.mp3";
    $out = fopen($fname, "wb");
    if ($out == FALSE) {
        die();
    }

    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => "https://texttospeech.responsivevoice.org/v1/text:synthesize?text=" . urlencode($text) . '&lang=' . urlencode($lang) . '&engine=g1&name=&pitch=0.5&rate=0.4&volume=1&key=0POmS5Y2&gender=female',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_FILE => $out,
        CURLOPT_HTTPHEADER => [
            "Host: texttospeech.responsivevoice.org",
        ],
    ]);

    $response = curl_exec($curl);
    $error = curl_error($curl);

    curl_close($curl);
    fclose($out);

    if ($error) {
        echo "cURL Error #:" . $error;
        die();
    }

    if (file_exists($fname)) {
        header('Content-Description: File Transfer');
        header('Content-Type: audio/mpeg');
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: ' . filesize($fname));
        ob_clean();
        flush();
        readfile($fname);
        exit;
    }
    die();
} else {
?>
    <html>

    <head>
        <style>
            textarea {
                border: 1px solid black;
                margin: 5px;
                padding: 5px;
                width: 40%;
            }
        </style>
    </head>

    <body>
        <form method="post">
            <textarea name="text">This is a sample text.</textarea><br>
            <select id="lang" name="lang">
                <option value="en-US">English</option>
                <option value="fr-FR">French</option>
            </select><br>
            <button id="sendbtn">submit</button><br>
        </form>
    </body>

    </html>
<?php
}
?>