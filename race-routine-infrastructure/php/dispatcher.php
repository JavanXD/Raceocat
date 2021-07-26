<?php

// enable PHP error reporting (only for testing)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: text/html; charset=utf-8');

// nocache
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

function flush_buffers(){
	@ob_end_flush();
	@ob_flush();
	flush();
	ob_start();
}

ob_implicit_flush(1);

?>
<!DOCTYPE html>
<html>
    <head>
        <title>Race Dispatcher</title>
        <meta charset="UTF-8">
		<script type="application/javascript">
		document.addEventListener("DOMContentLoaded", function(event) {
			document.getElementById("addServer").addEventListener("click", () => addServer());
			document.getElementById("removeServer").addEventListener("click", () => removeServer());
		});

		function addServer(server) {
			var div = document.createElement("div");
			var input = document.createElement("input");

			input.setAttribute("type", "text");
			input.setAttribute("size", "50");
			input.setAttribute("name", "server[]");
			div.appendChild(input);

			var serverBody = document.getElementById('serverBody');
			serverBody.appendChild(div);
		}
		function removeServer(server) {
			var serverBody = document.getElementById('serverBody');
			if(serverBody.childElementCount > 1) {
				serverBody.removeChild(serverBody.lastChild);
			}
		}
		</script>
		<style>
		input[type=submit].myAttack{
		    padding: 5px;
			background-color: orangered;
		    color: white;
		}
		</style>
    </head>
    <body>

<?php

echo '<form action="dispatcher.php" method="post">';
$default_host = $_SERVER['HTTP_HOST'];
$servers = isset($_REQUEST['server']) ? $_REQUEST['server'] : "https://".$default_host."/racer.php";

if (!is_array($servers) && strpos($servers, ',') !== false) {
    $servers = explode(',', trim(urldecode($servers)));
	$servers = array_filter($servers); // remove empty entries from array
}

$servers = is_array($servers) ? array_slice($servers, 0, 10) : [$servers];
echo '<fieldset>
	<legend>List of Servers</legend><div id="serverBody">';
foreach($servers as $server) {
	echo '<div><input type="text" name="server[]" value="'.htmlspecialchars($server).'" placeholder="https://example.org/racer.php" size="50"></div>';
	if (filter_var($server, FILTER_VALIDATE_URL) === FALSE) {
		die('<p>Not a valid Server-URL.</p>');
	}
}
echo '</div><button type="button" class="myDefault" id="addServer">Add Server</button><button type="button" class="myDefault" id="removeServer">Remove Server</button><br>';
echo "</fieldset>";
$proxy = isset($_REQUEST['proxy']) ? $_REQUEST['proxy'] : NULL;
echo '<fieldset>
	<legend>Proxy</legend>
	<input type="text" name="proxy" '.(isset($proxy) ? ' value="'.htmlspecialchars($proxy).'"' : "").' placeholder="localhost:8080 (keep empty if none)" size="50">
</fieldset>';

$payload = isset($_REQUEST['payload']) ? $_REQUEST['payload'] : '{"method":"GET","url":"https://'.$default_host.'/log.php"}';
echo '<fieldset>
	<legend>Payload</legend>
	<textarea name="payload" cols="50" rows="25">'.htmlspecialchars($payload).'</textarea></fieldset>';

$reqPerConnection = isset($_REQUEST['reqPerConnection']) ? $_REQUEST['reqPerConnection'] : 25;
echo '<fieldset>
	<legend>Requests per Connection</legend>
	<input type="int" name="reqPerConnection" '.(isset($reqPerConnection) ? ' value="'.(INT)$reqPerConnection.'"' : "").' placeholder="25" size="50">
</fieldset>';

$repeats = isset($_REQUEST['repeats']) ? $_REQUEST['repeats'] : 3;
echo '<fieldset>
	<legend>Repeats per Server</legend>
	<input type="int" name="repeats" '.(isset($repeats) ? ' value="'.(INT)$repeats.'"' : "").' placeholder="3" size="50">
</fieldset>';

echo '
	<input type="submit" name="submit" class="myAttack" value="Race it!">
</form>';

if (isset($_POST['payload']) && isset($_POST['submit'])) {

	$file = 'payload.log';
	$microtime = microtime(true);
	$time = date("Y-m-d h:i:sa");
	$ip = $_SERVER['REMOTE_ADDR'];
	$req = $microtime.' | '.$time.' | '.$ip.' | '.$payload."\r\n";
	$fp = file_put_contents($file, $req, FILE_APPEND);

	for ($j=0;$j<count($servers);$j++) {

		$ch[$j] = curl_init();

		curl_setopt($ch[$j], CURLOPT_URL, $servers[$j].'?reqPerConnection='.$reqPerConnection.'&repeats='.$repeats.(isset($proxy) ? '&proxy='.$proxy : ""));
		curl_setopt($ch[$j], CURLOPT_HEADER, false);
		curl_setopt($ch[$j], CURLOPT_POST, true);
		curl_setopt($ch[$j], CURLOPT_POSTFIELDS, "payload=".urlencode($payload));
		curl_setopt($ch[$j], CURLOPT_FOLLOWLOCATION, false);
	}

	$mh = curl_multi_init();

	flush_buffers();
	echo microtime(true)." @ Start Dispatcher<br />";
	flush_buffers();

	for ($j=0;$j<count($servers);$j++) {
		curl_multi_add_handle($mh,$ch[$j]);
	}

	$running=null;
	do {
	    usleep(1000);

	    $ausgabe = curl_multi_exec($mh,$running);
		if($ausgabe != '0'){
			echo '<p>'.$ausgabe.'</p>';
		}

	} while ($running > 0);

	flush_buffers();
	echo microtime(true)." @ Stop Dispatcher<br />";
	flush_buffers();

	for ($j=0;$j<count($servers);$j++) {
		curl_multi_remove_handle($mh, $ch[$j]);
	}

	curl_multi_close($mh);

}

?>
	</body>
</html>
