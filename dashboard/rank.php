<?php
require_once("../config.php");


function get_ranks($pgid)
	{
		$con = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD) or
		    die("Could not connect: " . mysql_error());
		mysql_select_db(DB_DATABASE, $con);
		
		/*
		Get the list of all users
		*/
		$result = mysql_query("SELECT account_pk, pgid, first_name, last_name FROM accounts");
		while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
			$row["points"] = 0;
			$users[$row["account_pk"]] = $row[];
		}
		
		$result = mysql_query( "SELECT account_fk, points FROM points");
		while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
			$users[$row["account_fk"]]["points"] += $row["points"];
		}
		
		foreach ($users as $user) {
			ranks[] = array("first_name" => user["first_name"],
				"last_name" => user["last_name"], "points" => user["points"],
				"pgid" => user["pgid"]);
		}
		
		for ($i = 0; $i < count($ranks); $i++)
			for ($j = 0; $j + 1 < count($ranks); $j++)
				if (ranks[$j]['points'] < ranks[$j + 1]['points']) {
					$tmp = $ranks[$j];
					$ranks[$j] = $ranks[$j + 1];
					$ranks[$j + 1] = $tmp;
				}
		for ($i = 0; $i < count($ranks); $i++)
			$ranks[$i]['rank'] = $i + 1;
		
		$n = count($users);
		if ($n > 10)
			$n = 10;
		foreach ($ranks as $rank)
			if ($rank["pgid"] == $pgid) {
				$ranks[$n] = $rank;
				break;
			}
		mysql_close($con);
		
		return array_slice($ranks, 0, $n + 1);
	}

?>