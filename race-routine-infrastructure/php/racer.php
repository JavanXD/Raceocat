<?php

// enable PHP error reporting (only for testing)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//print_r($_REQUEST);
//echo "<br>";

if (isset($_POST['payload'])) {
var_dump($_POST['payload']);
	$payload = json_decode($_POST['payload']);
//	var_dump($payload);
	$method = isset($payload->method) ? strtoupper($payload->method) : "";
	$url = isset($payload->url) ? $payload->url : "";
	$body = isset($payload->body) ? $payload->body : "";
	if (isset($payload->headers)) {
		$headers = $payload->headers;
		$curl_headers = Array();
		foreach($headers as $header){
			$curl_headers[] = $header->name.": ".$header->value;
		}
	}
	if (isset($_GET['proxy'])) {
		$proxy = $_GET['proxy'];
	}
	//$curl_cookie = isset($payload->headers["Cookies"]) ? $payload->headers["Cookies"] : NULL;
	//$curl_useragent = isset($payload->headers["User-Agent"]) ? $payload->headers["User-Agent"] : "RACE";
	//$curl_referer = isset($payload->headers["Referer"]) ? $payload->headers["Referer"] : NULL;

	$reqPerConnection = 10;
	$repeats = 2;

	if (filter_var($url, FILTER_VALIDATE_URL) === FALSE) {
		die('<p>Not a valid URL.</p>');
	}

	for ($j=0;$j<$reqPerConnection;$j++) {

		// Create cURL Resources
		$ch[$j] = curl_init();
		// Set Options
		curl_setopt($ch[$j], CURLOPT_URL, $url);

		if($method === 'POST'){
			curl_setopt($ch[$j], CURLOPT_POST, true);
			curl_setopt($ch[$j], CURLOPT_POSTFIELDS, $body);
		} else if ($method === 'PUT'){
			curl_setopt($ch[$j], CURLOPT_CUSTOMREQUEST, "PUT");
			curl_setopt($ch[$j], CURLOPT_POSTFIELDS, $body);
		} else {
			curl_setopt($ch[$j], CURLOPT_CUSTOMREQUEST, $method);
		}
		//if(isset($curl_cookie)){
		//	curl_setopt($ch[$j], CURLOPT_COOKIE, $curl_cookie);
		//}
		if(isset($proxy)){
			curl_setopt($ch[$j], CURLOPT_PROXY, $proxy);
			curl_setopt($ch[$j], CURLOPT_PROXY_SSL_VERIFYPEER, false);
		}
		curl_setopt($ch[$j], CURLOPT_HEADER, false);
		curl_setopt($ch[$j], CURLOPT_FOLLOWLOCATION, false);
		curl_setopt($ch[$j], CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch[$j], CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch[$j], CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch[$j], CURLOPT_TIMEOUT, 1);
		//if (isset($curl_referer)) { curl_setopt($ch[$j], CURLOPT_REFERER, $curl_referer); }
		//if (isset($curl_useragent)) { curl_setopt($ch[$j], CURLOPT_USERAGENT, $curl_useragent); }
		if (isset($curl_headers)) { curl_setopt($ch[$j], CURLOPT_HTTPHEADER, $curl_headers); }

	}

	for ($i=0;$i<$repeats;$i++) {
		// Create Multi Handle
		$mh = curl_multi_init();

		flush();
		echo microtime(true)." @ Start<br />";
		flush();

		// Add handles
		for ($j=0;$j<$reqPerConnection;$j++) {
			curl_multi_add_handle($mh,$ch[$j]);
		}

		$running = null;
		do {
		  curl_multi_exec($mh, $running);
		  curl_multi_select($mh);
		} while ($running > 0);
		// Run handle
		//do {
		//    usleep(10000);
		//    curl_multi_exec($mh,$running);
		//} while ($running > 0);

		flush();
		echo microtime(true)." @ Stop<br />";
		flush();

		// Close Handle
		for ($j=0;$j<$reqPerConnection;$j++) {
			curl_multi_remove_handle($mh, $ch[$j]);
		}
		curl_multi_close($mh);
	}

}
