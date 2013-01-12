<?php
require_once("../config.php");
$DB = new mysqli( DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE );
$query = 'SELECT * FROM bets WHERE state = 1';
if ($result = $DB->query($query)) 
{
	# get the bet personal keys
	$bets = array();
	while(($row = $result->fetch_assoc()) != false) {
		array_push($bets, $row);	
	}
	$result->free();

	$random_location = rand(1,100);
	$color = 2;

	if($random_location <= 50) {
		$color = 0; #black
	} elseif ($random_location <= 83) {
		$color = 1; #red
	} else {
		$color = 2;
	}

	$winners = array();
	foreach ($bets as $key => $bet) {
		$query = 'UPDATE bets SET state = 0 WHERE bets_pk = '.$bet['bets_pk'];
		// $DB->query($query);

		if($color == $bet['color']){
			$award = -1 * $bet['award'];
			$query = "INSERT INTO bets (user_fk, color, award, state) VALUES (".$bet['user_fk'].", ".$bet['color'].", $award, 0)";
			// $DB->query($query);	
			print_r($query);
			print "<db>";
		}
	}

	print_r($color);
	write_to_json($bets);
}
$DB->close();
function write_to_json($php_object) {
	$myFile = "wheel_results.json";
	$fh = fopen($myFile, 'w') or die("can't open file");
	fwrite($fh, json_encode($php_object));
	fclose($fh);
}
?>