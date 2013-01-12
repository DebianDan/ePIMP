<?php

$pk_id = $_POST['pk_id'];
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$twitter = $_POST['twitter'];

require_once("../config.php");
$DB = new mysqli( DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE );

$safe_first_name = $DB->real_escape_string($first_name);
$safe_last_name = $DB->real_escape_string($last_name);
$safe_email = $DB->real_escape_string($email);
$safe_phone = $DB->real_escape_string($phone);
$safe_twitter = $DB->real_escape_string($twitter);

$query = 'UPDATE accounts SET first_name = "'.$safe_first_name.'", last_name = "'.$safe_last_name.'", email = "'.$safe_email.'", phone_number = "'.$safe_phone.'", twitter = "'.$safe_twitter.'" WHERE PK = '.$pk_id;

echo $query;

$DB->query($query);

?>