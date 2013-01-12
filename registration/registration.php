<?php

#$token_id = $_POST["token"];
#$pgid = $_POST["pgid"];

$token_id = "e";
$pgid = 1;

require_once("../config.php");

$DB = new mysqli( DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE );

/* Prepare an insert statement */
$safe_token = $DB->real_escape_string($token_id);
$safe_pgid = $DB->real_escape_string($pgid);

$query = 'SELECT PK FROM accounts WHERE token = "'.$safe_token.'" AND pgid = "' . $safe_pgid . '"';

if ($result = $DB->query($query)) 
{
	$row = $result->fetch_assoc();
	$pk_id = $row['PK'];
	$result->free();
}
else
{
	$pk_id = 0;
}

if (trim($pk_id) === '')
{
	$query = 'INSERT INTO accounts(token,pgid) VALUES("'.$safe_token.'", "'. $safe_pgid . '")';
	$DB->query($query);
	
	$query = 'SELECT PK FROM accounts WHERE token = "'.$safe_token.'" AND pgid = "' . $safe_pgid . '"';
	$result = $DB->query($query);	
	$row = $result->fetch_assoc();
	$pk_id = $row['PK'];
	
	/* free result set */
    	$result->free();

	header("Location:/registration/index.html?pk_id=".$pk_id);
	$DB->close();

	exit;
}
else
{
	header("Location:/registration/pre_existing_user.html");
	$DB->close();

	exit;
}

?>
