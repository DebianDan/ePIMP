<?php
require_once('../config.php' );
$pgid = $_REQUEST['pgid'];
$token = $_REQUEST['token'];

$DB = new mysqli( DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE );
$safe_token = $DB->real_escape_string( $_REQUEST["token"] );
$safe_pgid = $DB->real_escape_string( $_REQUEST["pgid"] );

$selectQuery = 'SELECT count(1) from points inner join accounts on accounts.accounts_pk = points.accounts_fk where pgid=' . $safe_pgid .' and token = "' . $safe_token .'" and reason = "You made the right move."';
$result = $DB->query( $selectQuery );
if( $result->num_rows == 0 ){
    pimplog( 'Lucky user just made the right choice.  THE DAMN RIGHT CHOICE IF I DO SAY.' );
    $insertQuery = 'INSERT INTO points( accounts_fk, points, reason, created ) VALUES( (SELECT accounts_pk FROM accounts where pgid = ' . $safe_pgid .' and token = "' . $safe_token . '"), ' . JOBS . ', "You made the right move.", CURRENT_TIMESTAMP() ';
    $result = $DB->query( $insertQuery );
}

header('location: http://expensify.com/jobs' );
