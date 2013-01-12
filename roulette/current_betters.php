<?php
require_once("../config.php");
$DB = new mysqli( DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE );
$query = 'SELECT b.bets_pk, b.user_fk, b.color, b.award, p.first_name, p.last_name FROM bets b, accounts p WHERE b.state = 1 AND p.accounts_pk=b.user_fk';
if ($result = $DB->query($query)) 
{
	# get the bet personal keys
	$bets = array();
	while(($row = $result->fetch_assoc()) != false) {
		array_push($bets, $row);	
	}
	$result->free();

	echo json_encode($bets);
}
?>