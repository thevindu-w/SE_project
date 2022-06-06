<?php
require_once('utils/auth.php');
checkAuth();

/**
 * Finds a file name with given extension that does not exist in tmp/
 * @param string $extension file name extension.
 * @return string non existing file name
 */
function getNonExistingFileName(string $extension): string
{
    do {
        $i = rand(0, PHP_INT_MAX);
        $fname = $_SERVER['DOCUMENT_ROOT'] . "/tmp/audio$i.$extension";
    } while (file_exists($fname));
    return $fname;
}

/**
 * Creates a new file containing the audio generated from text in tmp/
 * This uses pico2wave to generate audio.
 * @param string $lang language of text.
 * @param string $text text to be converted to audio.
 * @return string|null audio file name if successfully generated the
 * audio, else returns null.
 */
function picoTTS(string $lang, string $text): ?string
{
    if (strlen($text) > 8192) return null;
    $fname = getNonExistingFileName('wav');
    $cmd = 'pico2wave -l ' . escapeshellarg($lang) . ' -w ' . escapeshellarg($fname) . ' ' . escapeshellarg($text);
    $output = [];
    exec($cmd, $output, $res_code);

    if (!file_exists($fname) || $res_code != 0) {
        if (file_exists($fname)) unlink($fname);
        return null;
    }
    return $fname;
}

/**
 * Creates a new file containing the audio generated from text in tmp/
 * This uses an external TTS service to generate audio.
 * @param string $lang language of text.
 * @param string $text text to be converted to audio.
 * @return string|null audio file name if successfully generated the
 * audio, else returns null.
 */
function externalTTS(string $lang, string $text): ?string
{
    if (strlen($text) > 1024) return null;
    $fname = getNonExistingFileName('mp3');
    $outfile = fopen($fname, "wb");
    if ($outfile == FALSE) {
        return null;
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
        CURLOPT_FILE => $outfile,
        CURLOPT_HTTPHEADER => [
            "Host: texttospeech.responsivevoice.org",
        ],
    ]);

    $response = curl_exec($curl);
    $error = curl_error($curl);

    curl_close($curl);
    fclose($outfile);

    if ($error) {
        if (file_exists($fname)) unlink($fname);
        return null;
    }
    return $fname;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['text']) && isset($_POST['lang']) && $_POST['text'] && $_POST['lang']) {
        require_once($_SERVER['DOCUMENT_ROOT'] . '/utils/languages.php');
        $text = $_POST['text'];
        $text = preg_replace('/\\s+/', ' ', $text);

        if (!file_exists('tmp')) {
            mkdir('tmp', 0777, true);
        }

        $fname = null; //file name of generated audio file

        // first try pico tts
        if (in_array($_POST['lang'], array_keys(LANG_TTS_PICO), true)) {
            $lang = LANG_TTS_PICO[$_POST['lang']];
            $fname = picoTTS($lang, $text);
        }

        // if pico tts was unsuccessful, try external tts
        if (!($fname && file_exists($fname)) && in_array($_POST['lang'], array_keys(LANG_TTS_EXT), true)) {
            $lang = LANG_TTS_EXT[$_POST['lang']];
            $fname = externalTTS($lang, $text);
        }

        if ($fname && file_exists($fname)) {
            header('Content-Description: File Transfer');
            header('Content-Type: audio/x-wav');
            header('Content-Transfer-Encoding: binary');
            header('Content-Length: ' . filesize($fname));
            ob_clean();
            flush();
            readfile($fname);
            unlink($fname);
            exit;
        }
    }
    die();
} else {
    header('Location: /grammar.php');
}
