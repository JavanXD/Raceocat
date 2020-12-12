<?php

// enable PHP error reporting (only for testing)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$file = 'request.log';
$microtime = microtime(true);
$time = date("Y-m-d h:i:sa");
$ip = $_SERVER['REMOTE_ADDR'];
$req = $microtime.' | '.$time.' | '.$ip."\r\n";
$fp = file_put_contents($file, $req, FILE_APPEND);

if (file_exists($file)) {

    echo "<pre>";
    echo file_get_contents($file, true);
    echo "</pre>";
}
