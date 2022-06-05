<?php
require_once('utils/auth.php');
checkAuth();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	if (isset($_POST['text']) && isset($_POST['lang']) && $_POST['text'] && $_POST['lang']) {
		require_once($_SERVER['DOCUMENT_ROOT'] . '/utils/languages.php');
		if (!in_array($_POST['lang'], array_keys(LANG_GRAMMAR), true)) {
			die();
		}
		$lang = LANG_GRAMMAR[$_POST['lang']];

		$text = $_POST['text'];
		if (strlen($text) > 65536) {
			die(); // too long text
		}

		$curl = curl_init();

		curl_setopt_array($curl, [
			CURLOPT_URL => "https://api.textgears.com/grammar",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 10,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => 'text=' . urlencode($text) . '&language=' . urlencode($lang) . "&whitelist=&dictionary_id=&key=DEMO_KEY",
			CURLOPT_HTTPHEADER => [
				"Host: api.textgears.com",
				"Referer: https://textgears.com/api",
			],
		]);

		$response = curl_exec($curl);
		$error = curl_error($curl);

		curl_close($curl);

		if ($error) {
			die();
		}

		$arr = json_decode($response, true);
		if ($arr['status'] === false) {
			die();
		}

		$res = $arr['response'];
		if ($res['result'] != 1) {
			die();
		}

		$errors = [];
		foreach ($res['errors'] as $err) {
			$sendErr = ['offset' => $err['offset'], 'length' => $err['length']];
			if (isset($err['better']) && $err['better']) {
				$sendErr['correct'] = $err['better'];
			}
			if (isset($err['description']) && $err['description'] && isset($err['description']['en']) && $err['description']['en']) {
				$sendErr['description'] = $err['description']['en'];
			}
			array_push($errors, $sendErr);
		}
		$off_arr = array_column($errors, 'offset');
		array_multisort($off_arr, SORT_ASC, $errors);
		echo json_encode($errors);
	}
} else {
	include "views/grammar.php";
}
