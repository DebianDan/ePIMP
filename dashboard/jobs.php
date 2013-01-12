<?php
require_once( '../config.php' );
    // Look up the user's PK
    $DB = new mysqli( DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE );
    $safe_token = $DB->real_escape_string( $_REQUEST["token"] );
    $safe_pgid = $DB->real_escape_string( $_REQUEST["pgid"] );
    $query = 'SELECT accounts_pk FROM accounts WHERE token = "'.$safe_token.'" AND pgid = "' . $safe_pgid . '"';
    $result = $DB->query( $query );
    $row = $result->fetch_assoc();
    $accountPK = $row['accounts_pk'];

    // Make sure we haven't already assigned this
    $message = "Made the right choice";
    $query = "SELECT points_pk FROM points WHERE reason='$message'";
    $result = $DB->query( $query );
    $row = $result->fetch_assoc();
    if( !$row["points_pk"] )
        $DB->query( "INSERT INTO points (accounts_fk, points, reason, created) VALUES ( $accountPK , '1000', '$message', CURRENT_TIMESTAMP );" );

    // Redirect to the true jobs page
    header( "location: https://www.expensify.com/jobs" );

