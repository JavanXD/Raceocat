<h2>Challenge 1: Bank account withdrawal</h2>

<a href="?accountID=1">See bank balance of accountID 1</a><br>

<a href="?accountID=1&amount=500">Withdrawal 500€</a><br>

<?php

// ?accountID=1 - Check your Balance
// ?accountID=1&amount=500 - Withdrawal 500€
// ?accountID=1&amount=-500 - Add 500€ to your bank account

if (isset($_REQUEST['accountID'])) {

    $accountID = (INT)$mysqli->real_escape_string($_REQUEST['accountID']);
    $result = $mysqli->query("SELECT balance FROM bank WHERE accountID = '$accountID' LIMIT 0,1");
    while($row = $result->fetch_assoc()) {
        $balance = $row["balance"];
        echo "Current balance: $balance €<br>";
    }

    if(isset($_REQUEST['amount'])) {
        $amount = intval($_REQUEST['amount']);
        echo "You are withdrawing: $amount <br>";
        $new_balance = $balance-$amount;
        race_window(RACE_WINDOW);
        if($new_balance >= 0) {
            $mysqli->query("UPDATE bank
                            SET balance = $new_balance
                            WHERE accountID = $accountID");
            if ($mysqli->affected_rows == 1)
            {
                echo "withdraw success, new bank balance is $new_balance €.<br>";
            }else{
                echo "accountID $accountID not found.<br>";
            }
        } else {
            echo "You don't have enought money in your bank account (currently: $new_balance €) to withdrawal that much.<br>";
        }
    }

}
