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

$mysqli->query("set profiling_history_size=100");
$mysqli->query("set profiling=1");

function close_mysql($mysqli)
{
    // show duration of each sql query
    $result = $mysqli->query("show profiles");
    if ($result->num_rows > 0) {
        echo "<p>Executed SQL queries:<br>";
        while ($row = $result->fetch_assoc())
        {
            echo "#".$row['Query_ID'].' - <b>'.round($row['Duration'],4) * 1000 .'</b> ms - "<code>'.$row['Query'].'</code>"<br>';
        }
        echo "</p>";
    }
    // close mysqli
    $mysqli->close();
    // show execution time
    echo "Processed in: ". bcsub(microtime(TRUE), "{$_SERVER['REQUEST_TIME_FLOAT']}", 6)*1000 . " ms<br>";

}
register_shutdown_function('close_mysql', $mysqli);
