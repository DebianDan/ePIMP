<?php

#$token_id = $_POST["token"];
#$pgid = $_POST["pgid"];

$token_id = "abcd";
$pgid = 1234;

require_once("../config.php");

$DB = new mysqli( DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE );

/* Prepare an insert statement */
$safe_token = $DB->real_escape_string($token_id);
$safe_pgid = $DB->real_escape_string($pgid);

$query = 'SELECT PK FROM accounts WHERE token = "'.$safe_token.'" AND pgid = "' . $safe_pgid . '"';

//echo "here<BR>";
//echo $query;

if ($result = $DB->query($query)) 
{
	$row = $result->fetch_assoc();
	$pk_id = $result['PK'];

	/* free result set */
    	$result->free();
}
else
{
	$pk_id = 0;
}

$DB->close();

if ($pk_id != 0)
{
	//echo "inside not found";
	header("Location:/registration/index.html?pkid=".$pk_id);
	exit;
}
else
{
	header("Location:/registration/pre_existing_user.html");
	exit;
	//echo "PK is 0<BR>";
}

?>
