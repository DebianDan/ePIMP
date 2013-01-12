<?php
function is_open($pgid, $mingle)
	{
		// open friendship
		if ($mingle["status"] == 0)
			return true;
		
		// friendship waiting for confirmation
		$curtime = gettimeofday(true);
		if ($mingle["status"] < 3 && $curtime - $mingle["time"] <= 61) {
			if ($pgid == $mingle["user_a"] && $mingle["status"] == 2)
				return true;
			if ($pgid == $mingle["user_b"] && $mingle["status"] == 1)
				return true;
		}
		
		// non-open
		return false;
	}
	
$con = mysql_connect("localhost", "mysql_user", "mysql_password") or
    die("Could not connect: " . mysql_error());
mysql_select_db("pimp", $con);

// Get all of the mingles involve current user.
$pgid = $_REQUEST["pgid"];
$result = mysql_query( "SELECT * FROM mingle_status WHERE user_a=" . $pgid . " OR user_b=" . $pgid);
// $friends is what you want
while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
	// eliminate non-open friendship
	if (is_open($pgid, $row))
		continue;

	if ($row["user_a"] == $pgid)
		$friend_id = $row["user_b"];
	else
		$friend_id = $row["user_a"];

	$frind_result = mysql_query("SELECT first_name, last_name, intro FROM accounts WHERE pgid=" . $friend_id);
	$friend_info = mysql_fetch_array($frind_result, MYSQL_ASSOC);
	$friends[] = array("pk" => $row["mingle_status_pk"], "pgid" => $friend_id, "first_name" => $friend_info["first_name"], "last_name" => $friend_info["last_name"], "info" => $friend_info["intro"]);
}

mysql_close($con);
?>