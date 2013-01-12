<?php

$pk_id = $_POST['pk_id'];
$token = $_POST['token'];
$pg_id = $_POST['pg_id'];
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$twitter = $_POST['twitter'];

require_once("../config.php");
$DB = new mysqli( DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);

$safe_first_name = $DB->real_escape_string($first_name);
$safe_last_name = $DB->real_escape_string($last_name);
$safe_email = $DB->real_escape_string($email);
$safe_phone = $DB->real_escape_string($phone);
$safe_twitter = $DB->real_escape_string($twitter);
$safe_pg_id = $DB->real_escape_string($pg_id);

// token is validated by _before.php.  it's clean.

$query = 'INSERT INTO accounts SET first_name = "'.$safe_first_name.'", last_name = "'.$safe_last_name.'", email = "'.$safe_email.'", phone_number = "'.$safe_phone.'", twitter = "'.$safe_twitter.'", token = "'.$token.'", pgid = "'.$safe_pg_id.'"';

$result = $DB->query($query);
if( $DB->error ){
    fatalErrorContactMatt( 'Insert acc:' . $DB->error );
}

$query = 'INSERT INTO points(accounts_fk, points, reason, created) VALUES (LAST_INSERT_ID(), ' . STARTING . ', "starting points", CURRENT_TIMESTAMP())';
$result = $DB->query( $query );
if( $DB->error ){
    fatalErrorContactMatt( 'Insert init pt:' . $DB->error );
}

// Send the welcome email
email_person( $pk_id, "Welcome", array(
    "name" => $first_name,
    "url" => "http://expensiparty.com?pgid=$pg_id&token=$token"
) );

header("Location:/?pgid=$_REQUEST[pg_id]&token=$_REQUEST[token]");
?>
