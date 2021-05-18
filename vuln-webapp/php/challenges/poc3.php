<h2>Challenge 3: Brute force 2FA code</h2>

<a href="?email=raceme@example.org">Check login log for raceme@example.org</a><br>

<a href="?email=raceme@example.org&code=0022">Try to login using 0022 as 2FA code</a><br>
<a href="?email=raceme@example.org&code=0012">Try to login using 0012 as 2FA code</a><br>

<?php

// PoC3: Advanced - Bruteforce 2FA code / bypass rate limit

if (isset($_REQUEST['email']) && filter_var($_REQUEST['email'], FILTER_VALIDATE_EMAIL)) {

    $email = $mysqli->real_escape_string($_REQUEST['email']);

    if (isset($_REQUEST['code'])) {

        $login_success = false;

        $code = (INT)$mysqli->real_escape_string($_REQUEST['code']);

        $result = $mysqli->query("SELECT COUNT(email)
                                    FROM logins
                                    WHERE email = '$email'
                                    AND timestamp < UNIX_TIMESTAMP()-5*60");
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
            echo "error while adding login. ".$mysqli->insert_id."<br>";
        }
    }

    $sql = "SELECT *
            FROM logins
            WHERE email = '$email'
            AND timestamp > UNIX_TIMESTAMP()-5*60
            ORDER BY timestamp ASC";
    $result = $mysqli->query($sql);

    if ($result->num_rows > 0) {
        echo "Das sind logins von letzten 5minuten:<br>";
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            echo "- ".$row['email']." login am ".$row['timestamp']." mit code ".$row['code']." war ".$row['success']."<br>";
        }
    } else {
        echo "No logins in last 5 minutes.<br>";
    }

}
