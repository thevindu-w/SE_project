<?php
require_once('utils/auth.php');
checkAuth();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$text = $_POST['text'];
	$lang = $_POST['lang'];

	// echo json_encode(["text" => $text]);
	// die();

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
		echo "cURL Error #:" . $error;
		die();
	}

	$arr = json_decode($response, true);
	if ($arr['status'] === false) {
		echo "status = false";
		die();
	}

	$res = $arr['response'];
	if ($res['result'] != 1) {
		echo "result = " . $res['result'];
		die();
	}

	$errors = [];
	foreach ($res['errors'] as $err) {
		array_push($errors, ['offset' => $err['offset'], 'length' => $err['length']]);
	}
	$off_arr = array_column($errors, 'offset');
	array_multisort($off_arr, SORT_ASC, $errors);
	echo json_encode($errors);
} else {
?>
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
		<script src="/script.js"></script>
		<script type="text/javascript">
			document.getElementById('imgbtn').onclick = e => {
				e.preventDefault();
				let formData = new FormData();
				formData.append("fileToUpload", document.getElementById("fileToUpload").files[0]);

				let xhr = new XMLHttpRequest();
				xhr.open("POST", "/image.php");
				xhr.onreadystatechange = async function() {
					if (xhr.readyState == XMLHttpRequest.DONE) {
						document.getElementById("txtdiv").innerText = this.response;
					}
				};
				xhr.send(formData);
			};
			document.getElementById('speakbtn').onclick = e => {
				e.preventDefault();
				let builder = new XHRBuilder();
				builder.addField('text', document.getElementById("txtdiv").innerText);
				builder.addField('lang', "en-US");
				let xhr = new XMLHttpRequest();
				xhr.open("POST", "/speak.php", true);
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