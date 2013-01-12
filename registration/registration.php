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
    header('location:/registration/pre_existing_user.html');
    exit;
}
else
{
	header("Location:/registration/register.html?token=".$safe_token."&pg_id=".$safe_pgid);
	exit;
}

?>
