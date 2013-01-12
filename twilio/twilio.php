<?php
require('twilio-php/Services/Twilio.php');

$sid = "AC9f9728ff495a697fdcd98a09ea005220"; // Your Account SID from www.twilio.com/user/account
$token = "42fbce0cb806c1f4d5e1a1388e9311bd"; // Your Auth Token from www.twilio.com/user/account
$client = new Services_Twilio($sid, $token);

function text_person($pk,$text) {
	$message = $client->account->sms_messages->create(
		'4159443971', // From a valid Twilio number
		'5124849744', // Text this number
		$text
	);
	
}

?>