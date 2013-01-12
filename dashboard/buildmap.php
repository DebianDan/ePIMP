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
Build adjacent map between users.
*/
foreach ($users as $user_a)
	foreach ($users as $user_b)
		$map[$user_a][$user_b] = 0;
foreach ($users as $user)
	$map[$user][$user] = 1;

/*
Get how many open friendship each specific user holding by
check the health of all friendship.
OPEN => friendship is not broken and the user haven't confirmed yet
*/
$result = mysql_query("SELECT * FROM mingle_status");
while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$map[$row["user_a"]][$row["user_b"]] = 1;
	$map[$row["user_b"]][$row["user_a"]] = 1;
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
foreach ($users as $user_a) {
	$got = true;
	while ($open_friend[$user_a] < 5 && $got) {
		$got = false;
		foreach ($users as $user_b)	
			if ($open_friend[$user_b] < 5 && $map[$user_a][$user_b] == 0) {
				// build new friendship
				$open_friend[$user_a]++;
				$open_friend[$user_b]++;
				$map[$user_a][$user_b] = 1;
				$map[$user_b][$user_a] = 1;
				// print_r($map);
				// play with db
        $query = "INSERT INTO mingle_status (user_a, user_b, status, time) VALUES ('" . $user_a . "', '" . $user_b . "', '0', CURRENT_TIMESTAMP)";
        // echo $query;
				mysql_query($query);
				$got = true;
				break;
			}
	}
}

mysql_close($con);

echo "done!";
?>