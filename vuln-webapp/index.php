<h1>Web App with Race Condition Vulnerabilities</h1>
<?php

// enable PHP error reporting (only for testing)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('_mysql.php');

function race_window($microseconds=0){
    // 100000 = 200ms
    // 1000000 = 1s
    if ($microseconds > 0) {
	       usleep($microseconds);
           echo "Added a race window to make testing easier. Time was ".round($microseconds/1000, 3)." ms.<br>";
    }

}
$race_window = isset($_REQUEST['race_window']) ? (INT)$_REQUEST['race_window'] : 200000;
define('RACE_WINDOW', $race_window);
echo "RACE_WINDOW is set to ".round($race_window/1000, 3)." ms.<br>";

include_once('examples/poc1.php');

include_once('examples/poc2.php');

include_once('examples/poc3.php');

?>
<hr>
<h2>Environment Info</h2>
<?php
echo 'PHP version: ' . phpversion() . '<br>';
echo 'MySql version: ' . $mysqli->server_info;
