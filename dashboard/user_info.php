<?php
require_once("../config.php");

function get_user_info($pgid)
	{
		$con = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD) or
		    die("Could not connect: " . mysql_error());
		mysql_select_db(DB_DATABASE, $con);

		$safe_pgid = mysql_real_escape_string($pgid);
		$query = 'SELECT * FROM accounts WHERE pgid = "' . $safe_pgid . '"';

		$result = mysql_query($query);
		$num_results = mysql_num_rows($result);
		/*
		If $num_result=0, the $pgid is incorrect.
		*/

		$row = mysql_fetch_array($result, MYSQL_ASSOC);
		mysql_close($con);
		
		return $row;
	}


//get params
$token = $_REQUEST["token"];
$pgid = $_REQUEST["pgid"];

$safe_token = mysql_real_escape_string($token);
$safe_pgid = mysql_real_escape_string($pgid);

$query = 'SELECT * FROM accounts WHERE token = "'.$safe_token.'" AND pgid = "' . $safe_pgid . '"';

$result = mysql_query($query);
$num_results = mysql_num_rows($result);

if ($num_results == 0) {
	echo "<p>You are not supposed to be here!</p>";
	die();
}

$info = get_user_info()

$accounts_pk = $info['accounts_pk'];
$first_name = $info['first_name'];
$last_name = $info['last_name'];
$play_mingle = $info['play_mingle'];

function get_total_points($pgid)
	{
		$con = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD) or
		    die("Could not connect: " . mysql_error());
		mysql_select_db(DB_DATABASE, $con);
		
		$safe_pgid = mysql_real_escape_string($pgid);
		$query = 'SELECT points FROM points WHERE accounts_fk=' . $accounts_pk;
		$result = mysql_query($query);
		$row = $result->fetch_assoc();
		$sum = 0;
		foreach($row as $pt)
			$sum += $pt;
		return $sum;
		
		mysql_close($con);
	}
?>