<?php

// Uncomment
$token_id = $_GET["token"];
$pgid = $_GET["pgid"];
$minor = $_GET["minor"];

if($minor === 'true')
	$minor = 1;
else
	$minor = 0;

require_once("../config.php");

$DB = new mysqli( DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE );

/* Prepare an insert statement */
$safe_token = $DB->real_escape_string($token_id);
$safe_pgid = $DB->real_escape_string($pgid);

$query = 'SELECT accounts_pk FROM accounts WHERE token = "'.$safe_token.'" AND pgid = "' . $safe_pgid . '"';

if ($result = $DB->query($query)) 
{
	$row = $result->fetch_assoc();
	$pk_id = $row['accounts_pk'];
	$result->free();
}
else
{
	$pk_id = '';
}

if (trim($pk_id) === '')
{
	$query = 'INSERT INTO accounts(token,pgid,minor) VALUES("'.$safe_token.'", "'. $safe_pgid . '",'.$minor.')';
	$DB->query($query);
	$query = 'SELECT accounts_pk FROM accounts WHERE token = "'.$safe_token.'" AND pgid = "' . $safe_pgid . '"';
	$result = $DB->query($query);	
	$row = $result->fetch_assoc();
	$pk_id = $row['accounts_pk'];
	$query = 'INSERT INTO points(accounts_fk, points, reason, created) VALUES (' . $pk_id . ', ' . STARTING . ', "starting points", CURRENT_TIMESTAMP())';
	$result = $DB->query($query);

	/* free result set */
    	$result->free();

	$DB->close();
	header("Location:/registration/register.html?pk_id=".$pk_id."&token=".$safe_token."&pg_id=".$safe_pgid);
	exit;
}
else
{
	$DB->close();
	header("Location:/registration/pre_existing_user.html");
	exit;
}

?>
