<?php
require_once( '../config.php' );
$money = 0;
$response = getBling($_REQUEST['pkid']);
if($response != null) {
	$money = $response;
}
echo $money;
?>