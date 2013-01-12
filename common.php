<?php
//set GMT time zone
date_default_timezone_set('Europe/London');

function fatalErrorContactMatt( $message, $sendSms = false ){
    echo '<h3>There was a fatal error</h3>';
    echo '<p>This was embarassing for us unless you\'re being cheeky. Your best bet here is to find an Expensify employee and ask them for Matt.</p>';
    echo '<p>Helpful information: ' . $message . '</p>';
    error_log( 'Fatal Error Contact Matt: ' . $message );

    if( $sendSms ){
        //9372398549 is matt
        text_person( null, 'ALERT:' . $message, 9372398549 );
    }

    die();
}

function getBling( $pk ){
    $DB = new mysqli( DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE );
    $account = intval( $pk );
    $result = $DB->query( 'SELECT sum( points ) as sum FROM points WHERE accounts_fk = ' . $account );
    $row = $result->fetch_assoc();
    if( !isset( $row['sum'] ) )
        return 0;
    return $row['sum'];
}

/*
 * Send a text message to a person given their PK
 *
 * ex.  text_person(7,"Hello World!");
 */
function text_person( $pk, $text, $phone_number = null ){
    /* Prepare an insert statement */
    $sid = "AC9f9728ff495a697fdcd98a09ea005220"; // Your Account SID from www.twilio.com/user/account
    $token = "42fbce0cb806c1f4d5e1a1388e9311bd"; // Your Auth Token from www.twilio.com/user/account
    if( $telephoneNumber == null ){
        $client = new Services_Twilio($sid, $token);
        $DB = new mysqli( DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE );
        $safePK = $DB->real_escape_string( $pk );

        /* Query their phone number */
        $query = 'SELECT phone_number FROM accounts WHERE accounts_pk = "'.$safePK. '"';
        $result = $DB->query($query);
        $row = $result->fetch_assoc();
        $phone_number = $row['phone_number'];
    }

    $message = $client->account->sms_messages->create(
        '4159443971', // From a valid Twilio number
        $phone_number, // Text this number
        $text
    );
    return $message;
}

function email_person( $pk, $template, $variables ){
    require_once "Mail.php";

    if( true ){
        $DB = new mysqli( DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE );
        $safePK = $DB->real_escape_string( $pk );

        $query = 'SELECT first_name, last_name, email FROM accounts WHERE accounts_pk = "' . $safePK . '"';
        $result = $DB->query( $query );
        $row = $result->fetch_assoc();

        $to = strip_tags( $row['first_name'] ) . ' ' . strip_tags( $row['last_name'] ) . ' <' . $row['email'] . '>';
    }
    else{
        $to = 'Matt McNamara <matt@expensify.com>';
    }

    $templateStuff = $_SERVER['DOCUMENT_ROOT'] . '/notifications/' . $template;
    if( !file_exists( $templateStuff . '.txt' ) || !file_exists( $templateStuff . '.subject' ) ){
        fatalErrorContactMatt( 'Unable to find notification by that name.', true );
    }

    $subject = file_get_contents( $templateStuff . '.subject' );
    $text    = file_get_contents( $templateStuff . '.txt' );

    $keys   = array_keys( $variables );
    $values = array_values( $variables );
    for( $c=0; $c<count($keys); ++$c ){
        $keys[$c] = "%$keys[$c]%";
    }

    $subject = str_replace( $keys, $values, $subject );
    $text    = str_replace( $keys, $values, $text );
    $from    = 'ExpensiParty <matt@expensify.com>';

    $headers = array ('From' => $from,
      'To' => $to,
      'Subject' => $subject);
    $smtp = Mail::factory('smtp',
      array ('host' => 'email-smtp.us-east-1.amazonaws.com',
        'auth' => true,
        'username' => 'AKIAJRLR2O6USXVH6KOQ',
        'password' => 'Av9VJWnHEmRmsguSuABCyIs6BzdOa+unctZfxxdPLBrA'));

    $mail = $smtp->send($to, $headers, $text);

    return true;
}

function getBeerpongPosition( $pgid, $token)
{
	$DB = new mysqli( DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE );

	if (mysqli_connect_errno()) {
		printf("Connect failed: %s\n", mysqli_connect_error());
		exit();
	}
  //find in beer pong
	$query = "select beer_pong.state, beer_pong.beer_pong_pk from beer_pong inner join accounts a on a.accounts_pk = user_a inner join accounts b on b.accounts_pk = user_b where ";
	$query = $query."(state = 1 or state =2) and ( ( a.pgid = '".$pgid."' and a.token = '".$token."' ) OR (b.pgid = '".$pgid."' and b.token = '".$token."' ) )";
  $result =  $DB->query($query);
	//echo "Query:" . $query . "<BR>";
	//in queue
	$row = $result->fetch_assoc();

	if ($row['state'] == 1 || $row['state'] == 2)
	{
		$bp_pk = $row['beer_pong_pk'];
		$query = "SELECT beer_pong_pk FROM beer_pong WHERE state = 2 OR state = 1 ORDER BY beer_pong_pk ASC";
		$result = $DB->query($query);
		$pos = 0;

		while($row = $result->fetch_assoc())
		{
			$pos = $pos + 1;
			if ($row['beer_pong_pk'] == $bp_pk) {
				break;
			}
		}
		if($pos == 1 && $pos == 2)
				return 0;
		return $pos - 2;

	}
	else
		return -1;
  mysql_close($DB);
}

function getPhotoshopPosition( $pgid, $token)
{
	$DB = new mysqli( DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE );

	if (mysqli_connect_errno()) {
		printf("Connect failed: %s\n", mysqli_connect_error());
		exit();
	}
	//find in photoshop
	$query = "SELECT * FROM photoshop ps JOIN accounts a ON ps.users_fk = a.accounts_pk WHERE pgid='". $pgid . "' AND token='" .$token. "' ORDER BY photoshop_pk DESC LIMIT 1";
	$result =  $DB->query($query);
	//in queue
	$row = $result->fetch_assoc();
	$pos = 0;

	if ($row['state'] == 1)
	{
		$accounts_pk = $row['accounts_pk'];
		$query = "SELECT photoshop_pk, users_fk FROM photoshop WHERE state=1 ORDER BY photoshop_pk ASC";
		$result = $DB->query($query);

		while ($row = $result->fetch_assoc())
		{
			$pos = $pos + 1;;
			if($row['users_fk'] == $accounts_pk){
				break;
			}
		}
		return $pos;
	}
	else
		return -1;
  mysql_close($DB);

}