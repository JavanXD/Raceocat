<h2>PoC2</h2>

<a href="?postingID=1">Sehe Likes von Posting 1</a><br>

<a href="?postingID=1&userID=5">Sehe Likes von Posting 1 und füge Like hinzu mit userID 5</a><br>

<?php

// PoC2: like a twitter feed (multiple poll votes)

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

    if ($result->num_rows > 0) {
        echo "Das sind alle likes:<br>";
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            echo $row['postingID']." geliked von ".$row['userID']."<br>";
        }
    } else {
        echo "No likes for this posting.<br>";
    }
}
