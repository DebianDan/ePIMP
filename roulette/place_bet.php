<?php

if(isset($_REQUEST['pkid'])) {
	require_once("../config.php");
	$DB = new mysqli( DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE );


	/* Prepare an insert statement */
	$safe_token = $DB->real_escape_string($_REQUEST['token']);
	$safe_pgid = $DB->real_escape_string($_REQUEST['pgid']);
	$safe_pkid = $DB->real_escape_string($_REQUEST['pkid']);
	$safe_amount = $DB->real_escape_string($_REQUEST['amount']);
	$safe_amount = -1 * abs($safe_amount);
	$safe_color = $DB->real_escape_string($_REQUEST['color-choice']);

	$query = "INSERT INTO bets (user_fk, color, award, state) VALUES ($safe_pkid, $safe_color, $safe_amount, 1)";
	$DB->query($query);
}

header("Location:/?pgid=$_REQUEST[pgid]&token=$_REQUEST[token]");
?>