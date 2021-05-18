<h2>Challenge 2: Multiple poll votes</h2>

<p>You are only allowed to like a postingID once. Similar to a facebook or twitter like.</p>

<a href="?postingID=1">View all the likes of postingID 1</a><br>
Action: <a href="?postingID=1&userID=5">Like postingID 1 with userID 5</a><br>

<?php

// ?postingID=1 - View all the likes by all users for a selected postingID
// ?postingID=1&userID=1 - Like a posting

// For example if you are only allowed to vote once

if (isset($_REQUEST['postingID'])) {

    $postingID = (INT)$mysqli->real_escape_string($_REQUEST['postingID']);

    if (isset($_REQUEST['userID'])) {

        $userID = (INT)$mysqli->real_escape_string($_REQUEST['userID']);

        $sql = "SELECT postingID, userID
                FROM likes
                WHERE userID = $userID
                AND postingID = $postingID";
        $result = $mysqli->query($sql);

        if ($result->num_rows == 0) {
            echo "Not liked this post before, add you to the likers.<br>";
            race_window(RACE_WINDOW);
            $mysqli->query("INSERT INTO likes (postingID, userID) VALUES ($postingID, $userID)");
            if ($mysqli->insert_id) {
                echo "Liked success.<br>";
            } else {
                echo "error while liking. ".$mysqli->insert_id."<br>";
            }

        } else {
            echo "Already liked, can't like twice.<br>";
        }
    }

    $sql = "SELECT postingID, userID
            FROM likes
            WHERE postingID = $postingID";
    $result = $mysqli->query($sql);

    echo "The posting with postingID ".$postingID." was liked by the following people:<br>";
    if ($result->num_rows > 0) {    
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            echo "- Liked by userID ".$row['userID']."<br>";
        }
        echo "Total likes: " . $result->num_rows . "<br>";
    } else {
        echo "- Noone liked this posting yet, be the first one.<br>";
    }
}
