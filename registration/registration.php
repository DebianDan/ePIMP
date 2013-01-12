<?php

// Uncomment
$token_id = $_GET["token"];
$pgid = $_GET["pgid"];
$minor = $_GET["minor"];

require_once("../config.php");

$DB = new mysqli( DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE );

/* Prepare an insert statement */
$safe_token = $DB->real_escape_string($token_id);
$safe_pgid = $DB->real_escape_string($pgid);

$query = 'SELECT accounts_pk FROM accounts WHERE token = "'.$safe_token.'" AND pgid = "' . $safe_pgid . '"';
echo $query;

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

echo $pk_id;

if (trim($pk_id) === '')
{
	$query = 'INSERT INTO accounts(token,pgid,minor) VALUES("'.$safe_token.'", "'. $safe_pgid . '",'.$minor.')';
	$DB->query($query);
	
	$query = 'SELECT accounts_pk FROM accounts WHERE token = "'.$safe_token.'" AND pgid = "' . $safe_pgid . '"';
	$result = $DB->query($query);	
	$row = $result->fetch_assoc();
	$pk_id = $row['accounts_pk'];
	
	/* free result set */
    	$result->free();

	header("Location:/registration/register.html?pk_id=".$pk_id);
	$DB->close();

	exit;
}
else
{
//	header("Location:/registration/pre_existing_user.html");
	$DB->close();
	exit;
}

?>
