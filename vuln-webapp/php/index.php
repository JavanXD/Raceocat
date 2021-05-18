<h1>Web App with Race Condition vulnerabilities</h1>
<?php

require_once('_vars.php');
require_once('_mysql.php');

echo "RACE_WINDOW is set to ".round(RACE_WINDOW/1000, 3)." ms.<br>";

include_once('challenges/poc1.php');

include_once('challenges/poc2.php');

include_once('challenges/poc3.php');

?>
<h2>Debug info</h2>
<?php
echo 'PHP version: ' . phpversion() . '<br>';
echo 'MySql version: ' . $mysqli->server_info . '<br>';
