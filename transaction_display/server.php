<?php
require_once("../config.php");
$con = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD) or
    die("Could not connect: " . mysql_error());
mysql_select_db(DB_DATABASE, $con);

/*
Get the list of all transactions
*/
$result = mysql_query("SELECT * FROM points ORDER BY created DESC LIMIT 1");
$row = mysql_fetch_array($result, MYSQL_ASSOC);
$query = "select first_name, last_name FROM accounts WHERE accounts_pk = '" . $row['accounts_fk'] . "'";
$result_name = mysql_query($query);
$name = mysql_fetch_array($result_name, MYSQL_ASSOC);
$trans[] = array("first_name" => name['first_name'], "last_name" => name['last_name'], "points" => row['points'], "reason" => row['reason'], "time" => row['created']);
header( 'Content-type: application/json');
echo json_encode( $trans );