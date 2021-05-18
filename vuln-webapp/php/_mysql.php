<?php

// MySql secrets
define('DB_HOST', 'mariadb');
define('DB_USER', 'root');
define('DB_PASSWORD', 'qwerty');
define('DB_DATABASE', 'vulnwebapp');

$mysqli = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE, 3306);

// Check connection
if (mysqli_connect_errno())
{
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit;
}

function close_mysql($mysqli)
{
    $mysqli->close();
    echo "Processed in: ". bcsub(microtime(TRUE), "{$_SERVER['REQUEST_TIME_FLOAT']}", 4)*1000 . " ms<br>";
}
register_shutdown_function('close_mysql', $mysqli);
