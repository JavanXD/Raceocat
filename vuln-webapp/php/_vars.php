<?php

// enable PHP error reporting (only for testing)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function race_window($microseconds=0){
    // 100000 = 200ms
    // 1000000 = 1s
    if ($microseconds > 0) {
	       usleep($microseconds);
           echo "Executed a artifically race window of ".round($microseconds/1000, 3)." ms.<br>";
    }

}

$race_window = isset($_REQUEST['race_window']) ? (INT)$_REQUEST['race_window'] : 50000;
define('RACE_WINDOW', $race_window);
