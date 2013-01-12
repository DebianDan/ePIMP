<?php
require_once("../config.php");
$con = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD) or
    die("Could not connect: " . mysql_error());
mysql_select_db(DB_DATABASE, $con);

//get params
$token = $_REQUEST["token"];
$pgid = $_REQUEST["pgid"];

$safe_token = $DB->real_escape_string($token);
$safe_pgid = $DB->real_escape_string($pgid);

$query = 'SELECT accounts_pk FROM accounts WHERE token = "'.$safe_token.'" AND pgid = "' . $safe_pgid . '"';

$result = mysql_query($query);
$num_results = mysql_num_rows($result);
/*
If $num_result=0, the token is incorrect.
*/
if ($num_results == 0) {
  echo "<html><h1>Hello, something went wrong with your request!</h1></html>";
  die;
}

$row = $result->fetch_assoc();
$first_name = $row['first_name'];
$last_name = $row['last_name'];

mysql_close($con);
?>