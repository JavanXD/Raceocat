<h1>Vulnerable Web App</h1>
<p>There are three challenges, all of them vulnerable to race conditions. You can try to exploit the race condition weaknesses with tools such as <a href="https://github.com/JavanXD/Raceocat/" target="_blank" rel="noopener">Raceocat</a>.</p>
<?php

require_once('_vars.php');
require_once('_mysql.php');

echo "<p><i>RACE_WINDOW</i> is <b>".round(RACE_WINDOW/1000, 3)."</b> ms.<br>
 For this testing environment a artifically race window might be required because this application is created with a small sample data set. By increasing the <i>RACE_WINDOW</i> value you can simulate a slow webserver or a unperformant database and increase your chances. You can change or disable it by adding <code>?race_window=0</code> (in microseconds) as parameter.</p>";

include_once('challenges/poc1.php');

include_once('challenges/poc2.php');

include_once('challenges/poc3.php');

?>
<h2>Debug info</h2>
<p>
<?php
echo 'PHP version: <i>' . phpversion() . '</i><br>';
echo 'MySql version: <i>' . $mysqli->server_info . '</i><br>';
?>
</p>
