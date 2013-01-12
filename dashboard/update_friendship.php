<?php
require_once("user_info.php");
require_once("../config.php");


$con = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD) or
    die("Could not connect: " . mysql_error());
mysql_select_db(DB_DATABASE, $con);


function get_points($friend_count)
	{
		if ($friend_count > 10)
			return 0;
		else
			return pow(2, 10 - $friend_count);
	}

function get_accounts_pk($pgid)
	{
		// global $con;
		// todo
		$result = mysql_query("SELECT accounts_pk FROM accounts WHERE pgid='" . $pgid . "'");
		$row = mysql_fetch_array($result, MYSQL_NUM);
		return $row[0];
	}

$mingle_status_pk = $_REQUEST["mingle_status_pk"];
$pgid_a = $_REQUEST["pgid"]; // $pgid_a is current user
$pgid_b = $_REQUEST["friendID"];

$result = mysql_query( "SELECT * FROM mingle_status WHERE mingle_status_pk='" . $mingle_status_pk . "'");
$row = mysql_fetch_array($result, MYSQL_ASSOC);

//echo "status: " . $row["status"];
if ($row["status"] == 3) {
	header("Location:index.php?". $_SERVER['QUERY_STRING']);
	die();
} if ($row["status"] > 0 && time() - strtotime($row['time']) > 61) {// friendship broken
  //echo $row['time'] . "<br />";
  //echo strtotime( $row['time']) . "<br />";
  //echo time() - strtotime($row['time']) . "<br />";
	//echo "quit" . "<ˇbr />";
	header("Location:index.php?". $_SERVER['QUERY_STRING']);
	die();
}

$status = $row["status"];
if ($row["user_a"] == $pgid_a) {
	if ($status % 2 == 0)
		$status += 1;
} else {
	if ($status < 2)
		$status += 2;
}

$query = "UPDATE mingle_status SET status = '" . $status . "', time = CURRENT_TIMESTAMP WHERE mingle_status_pk = '" . $mingle_status_pk . "'";
mysql_query($query);

// update points
if ($status == 3) {
	$friend_count_a = 0;
	$friend_count_b = 0;

	$result = mysql_query( "SELECT * FROM mingle_status WHERE user_a = '" . $pgid_a . "' OR user_a = '" . $pgid_b . "' OR user_b = '" . $pgid_a . "' OR user_b = '" . $pgid_b . "'");
	while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
		if ($row["status"] % 2 == 1) {
			if ($row["user_a"] == $pgid_a)
				$friend_count_a++;
			else if ($row["user_a"] == $pgid_b)
				$friend_count_b++;
		}
		if ($row["status"] >= 2) {
			if ($row["user_b"] == $pgid_a)
				$friend_count_a++;
			else if ($row["user_b"] == $pgid_b)
				$friend_count_b++;
		}
	}

	$pk_a = get_accounts_pk($pgid_a);
	$pk_b = get_accounts_pk($pgid_b);
	$score_a = get_points($friend_count_a);
	$score_b = get_points($friend_count_b);
	$curtime = gettimeofday(true);
	if ($score_a > 0)
		mysql_query("INSERT INTO points (accounts_fk, points, reason, created) VALUES ('" . $pk_a . "', '" . $score_a . "', 'Mingling with " . $pk_b . "', CURRENT_TIMESTAMP)");
	if ($score_b > 0)
		mysql_query("INSERT INTO points (accounts_fk, points, reason, created) VALUES ('" . $pk_b . "', '" . $score_b . "', 'Mingling with " . $pk_a . "', CURRENT_TIMESTAMP)");
}

mysql_close($con);
header("Location:index.php?". $_SERVER['QUERY_STRING']);

?>