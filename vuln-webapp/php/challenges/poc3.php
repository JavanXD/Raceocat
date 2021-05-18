<h2>Challenge 3: Brute force 2FA code</h2>

<p>To slow down brute forcing attacks you are only allowed to login three times per 5 minutes.</p>

<a href="?email=raceme@example.org">View login log for <i>raceme@example.org</i></a><br>
Action: <a href="?email=raceme@example.org&code=0022">Try to login using 0022 as 2FA code</a><br>
Action: <a href="?email=raceme@example.org&code=0012">Try to login using 0012 as 2FA code</a><br>

<?php

// PoC3: Advanced - Bruteforce 2FA code / bypass rate limit

if (isset($_REQUEST['email']) && filter_var($_REQUEST['email'], FILTER_VALIDATE_EMAIL)) {

    $email = $mysqli->real_escape_string($_REQUEST['email']);

    if (isset($_REQUEST['code'])) {

        $login_success = false;

        $code = (INT)$mysqli->real_escape_string($_REQUEST['code']);

        $result = $mysqli->query("SELECT *
                                    FROM logins
                                    WHERE email = '$email'
                                    AND timestamp > UNIX_TIMESTAMP()-5*60");
        race_window(RACE_WINDOW);
        if ($result->num_rows < 3) {
            echo "Less then 3 logins in last minute, everything OK.<br>";
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

        } else {
            echo "Login limit reached, request blocked.<br>";
            $login_success = false;
        }

        $mysqli->query("INSERT INTO logins (email, code, success) VALUES ('$email', '$code', '".(INT)$login_success."')");
        if ($mysqli->insert_id) {
            echo "Added login try.<br>";
        } else {
            echo "Error while adding login. ".$mysqli->insert_id."<br>";
        }
    }

    $sql = "SELECT *
            FROM logins
            WHERE email = '$email'
            AND timestamp >= UNIX_TIMESTAMP()-5*60
            ORDER BY timestamp DESC
            LIMIT 0,30";
    $result = $mysqli->query($sql);

    if ($result->num_rows > 0) {
        echo "Das sind logins von letzten 5minuten:<br>";
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            echo "- <i>".$row['email']."</i> tried to login at ".$row['timestamp']." by using the 2FA code ".$row['code']." and was ".(($row['success']) ? "successfull": "unsuccessfull" )."<br>";
        }
    } else {
        echo "No logins in the last 5 minutes.<br>";
    }

}
