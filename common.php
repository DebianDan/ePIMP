<?php

function fatalErrorContactMatt( $message ){
    echo '<h3>There was a fatal error</h3>';
    echo '<p>This was embarassing for us unless you\'re being cheeky. Your best bet here is to find an Expensify employee and ask them for Matt.</p>';
    echo '<p>Helpful information: ' . $message . '</p>';
    die();
}

/*
 * Send a text message to a person given their PK
 *
 * ex.  text_person(7,"Hello World!");
 */
function text_person($pk,$text) {
    /* Prepare an insert statement */
    $sid = "AC9f9728ff495a697fdcd98a09ea005220"; // Your Account SID from www.twilio.com/user/account
    $token = "42fbce0cb806c1f4d5e1a1388e9311bd"; // Your Auth Token from www.twilio.com/user/account
    $client = new Services_Twilio($sid, $token);
    $DB = new mysqli( DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE );

    /* Query their phone number */
    $query = 'SELECT phone_number FROM accounts WHERE accounts_pk = '.$pk;
    $result = $DB->query($query);
    $row = $result->fetch_assoc();
    $phone_number = $row['phone_number'];


    $message = $client->account->sms_messages->create(
        '4159443971', // From a valid Twilio number
        $phone_number, // Text this number
        $text
    );
    return $message;
}
