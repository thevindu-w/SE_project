<?php
require_once('utils/auth.php');
checkAuth();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $text = $_POST['text'];
    $lang = $_POST['lang'];

    $fname = "tmp/audio.mp3";
    unlink($fname);
    
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
            <textarea id="txtArea" name="text">This is a sample text.</textarea><br>
            <select id="lang" name="lang">
                <option value="en-US">English</option>
                <option value="fr-FR">French</option>
            </select><br>
            <button id="sendbtn">submit</button><br>
        </form>
        <script src="/script.js"></script>
        <script type="text/javascript">
            document.getElementById('sendbtn').onclick = e => {
                e.preventDefault();
                let builder = new XHRBuilder();
                builder.addField('text', document.getElementById("txtArea").textContent);
                builder.addField('lang', "en-US");
                let xhr = new XMLHttpRequest();
                xhr.open("POST", document.URL, true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.responseType = 'blob';
                xhr.onreadystatechange = async function() {
                    if (xhr.readyState == XMLHttpRequest.DONE) {
                        let cont_type = xhr.getResponseHeader('Content-Type');
                        if (cont_type === 'audio/mpeg') {
                            let blob = new Blob([this.response], {
                                type: 'audio/mpeg'
                            });
                            let aud = document.createElement("audio");
                            aud.style = "display: none";
                            document.body.appendChild(aud);
                            let url = window.URL.createObjectURL(blob);
                            aud.src = url;
                            aud.onload = evt => {
                                URL.revokeObjectURL(url);
                            };
                            aud.onended = evt => {
                                document.body.removeChild(aud);
                            }
                            aud.play();
                        } else {
                            console.log("error");
                        }
                    }
                };
                xhr.send(builder.build());
            };
        </script>
    </body>

    </html>
<?php
}
?>