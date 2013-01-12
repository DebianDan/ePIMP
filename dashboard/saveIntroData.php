<?php
require_once("user_info.php");

$con = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD) or
    die("Could not connect: " . mysql_error());
mysql_select_db(DB_DATABASE, $con);

$intro = $_REQUEST["intro"];
$optout = $_REQUEST["optout"];
$play = 1;
if ($optout)
	$play = 2;

$safe_pgid = mysql_real_escape_string($pgid);
$query = 'UPDATE accounts SET intro="' . $intro . '", play_mingle=' . $play . ' WHERE $pgid = $safe_pgid';
mysql_query($query);

http_redirect(".", array("pgid" => $pgid, "token" => $token));
?>