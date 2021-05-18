<h2>Challenge 3: Brute force 2FA code</h2>
<?php
$limit_logins = 5;
$limit_seconds = 5*60;
?>
<p>To slow down brute forcing attacks you are only allowed to login <?=$limit_logins?> times per <?=($limit_seconds/60)?> minutes.</p>

<p>
    <a href="?email=raceme@example.org">View login log for <i>raceme@example.org</i></a><br>
    Action: <a href="?email=raceme@example.org&code=0022">Try to login using 0022 as 2FA code</a><br>
    Action: <a href="?email=raceme@example.org&code=0012">Try to login using 0012 as 2FA code</a>
</p>

<?php

if (isset($_REQUEST['email']) && filter_var($_REQUEST['email'], FILTER_VALIDATE_EMAIL)) {

    $email = $mysqli->real_escape_string($_REQUEST['email']);

    if (isset($_REQUEST['code'])) {

        $code = (INT)$mysqli->real_escape_string($_REQUEST['code']);

        $result = $mysqli->query("SELECT *
                                    FROM logins
                                    WHERE email = '$email'
                                    AND UNIX_TIMESTAMP(`timestamp`) >= UNIX_TIMESTAMP()-$limit_seconds");
        race_window(RACE_WINDOW);
        if ($result->num_rows < $limit_logins) {
            $login_success = false;
            //echo "Less then $limit_logins logins in last minute, everything OK.<br>";
            $result = $mysqli->query("SELECT userID
                            FROM user
                            WHERE email = '$email'
                            AND code = $code LIMIT 0,1");
            if ($result->num_rows == 1) {
                echo "Login success.<br>";
                $login_success = true;
            } else {
                echo "Wrong code.<br>";
                $login_success = false;
            }

            $mysqli->query("INSERT INTO logins (email, code, success) VALUES ('$email', '$code', '".(INT)$login_success."')");
            if ($mysqli->insert_id) {
                //echo "You tried to login. Added login to the log.<br>";
            } else {
                echo "Error while adding login. ".$mysqli->insert_id."<br>";
            }

        } else {
            echo "Login limit of $limit_logins tries reached, request blocked.<br>";
        }

    }

    $sql = "SELECT *
            FROM logins
            WHERE email = '$email'
            AND UNIX_TIMESTAMP(timestamp) >= UNIX_TIMESTAMP()-$limit_seconds
            ORDER BY timestamp DESC
            LIMIT 0,10";
    $result = $mysqli->query($sql);

    if ($result->num_rows > 0) {
        echo "List of logins within the last ".($limit_seconds/60)." minutes:<br>";
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            echo "- <i>".htmlspecialchars($row['email'])."</i> tried to login at ".$row['timestamp']." by using the 2FA code ".$row['code']." and was ".(($row['success']) ? "successfull": "unsuccessfull" )."<br>";
        }
    } else {
        echo "No logins in the last ".($limit_seconds/60)." minutes.<br>";
    }

}
