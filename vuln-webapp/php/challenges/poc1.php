<h2>Challenge 1: Bank account withdraw</h2>

<p>You can withdraw only enough money so that your bank account is not in the negative. Your bank account can not overspend.</p>

<p>
    <a href="?accountID=1">View bank account balance of accountID 1</a><br>
    <a href="?accountID=1">View bank account balance of accountID 2</a><br>
    Action: <a href="?accountID=1&amount=500">Withdraw 500€ from accountID 1</a>
</p>
<?php

if (isset($_REQUEST['accountID'])) {

    $accountID = (INT)$mysqli->real_escape_string($_REQUEST['accountID']);
    $result = $mysqli->query("SELECT balance FROM bank WHERE accountID = '$accountID' LIMIT 0,1");
    while($row = $result->fetch_assoc()) {
        $balance = $row["balance"];
        echo "Current balance: $balance €<br>";
    }

    if(isset($_REQUEST['amount'])) {
        $amount = (INT)$_REQUEST['amount'];
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
