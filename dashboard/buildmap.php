<?php
require_once("../config.php");
$con = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD) or
    die("Could not connect: " . mysql_error());
mysql_select_db(DB_DATABASE, $con);

/*
Get the list of all users
*/
$result = mysql_query("SELECT pgid FROM accounts WHERE play_mingle = '1'");
while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
	$users[] = $row[0];
	$open_friend[$row[0]] = 0;
}

/*
Get how many open friendship each specific user holding by
check the health of all friendship.
OPEN => friendship is not broken and the user haven't confirmed yet
*/
$result = mysql_query("SELECT * FROM mingle_status");
while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$curtime = gettimeofday(true);
	$status = $row["status"];
	switch ($status) {
		case 1: // A confirms
		case 2: // B confirms
			if ($status == 1)
				$idle_user = $row["user_b"];
			else
				$idle_user = $row["user_a"];
			// If one of the users confirmed more than 60 secs ago,
			// friendship is broken. Otherwise, it is open.
			if ($curtime - $row["time"] <= 61.0) // not broken
				$open_friend[$idle_user]++;
			break;
		case 3: // complete
			break;
		default: // open i.e. status = 0
			$open_friend[$row["user_a"]]++;
			$open_friend[$row["user_b"]]++;
	}
}


/*
Find new friends (to meet) for each user who has less than 5 friends.
*/
foreach ($users as $user_a)
	if ($open_friend[$user_a] < 5)
		foreach ($users as $user_b)
			if ($open_friend[$user_b] < 5 && $user_b != $user_a) {
				// build new friendship
				$open_friend[$user_a]++;
				$open_friend[$user_b]++;
				// play with db
				$curtime = gettimeofday(true);
        $query = "INSERT INTO mingle_status (user_a, user_b, status, time) VALUES ('" . $user_a . "', '" . $user_b . "', '0', '" . $curtime . "')";
        echo $query;
				mysql_query($query);

        echo 'Error: ' . mysql_error();
			}

mysql_close($con);

echo "done!";
?>