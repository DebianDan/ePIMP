<?php

if(isset($_REQUEST['pkid'])) {
	require_once("../config.php");
	$DB = new mysqli( DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE );


	/* Prepare an insert statement */
	$safe_token = $DB->real_escape_string($_REQUEST['token']);
	$safe_pgid = $DB->real_escape_string($_REQUEST['pgid']);
	$safe_pkid = $DB->real_escape_string($_REQUEST['pkid']);
	$safe_bet = $DB->real_escape_string($_REQUEST['amount']);
	$safe_bet = -1 * abs($safe_bet);
	$safe_color = $DB->real_escape_string($_REQUEST['color-choice']);

	$total_bling = getBling($safe_pkid);
	if($total_bling + $safe_bet >= 0) {
        
		$query = "INSERT INTO bets (user_fk, color, award, state) VALUES ($safe_pkid, '$safe_color', $safe_bet, 1)";
        $DB->query($query);	
		$query = "INSERT INTO points(accounts_fk, points, reason) values ($safe_pkid, $safe_bet, 'Put " . abs($safe_bet) . " Bling on $safe_color...')";
        $DB->query($query);	
	}
}

header("Location:/?pgid=$_REQUEST[pgid]&token=$_REQUEST[token]");
?>
