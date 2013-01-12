<?php
// generic registration stuff here.

if( !file_exists( 'config.php' ) ){
    die( 'Config file doesn\' exist.  Did you forget to copy config.php.default to config.php?');
}

require_once( 'config.php' );

// Dispatch according to the cookie, if specified.  Every party tablet will have a cookie
// indicating which function it is supposed to serve.  If there is no cookie, it means the
// user has tapped on their personal device.
$cookie = $_REQUEST['dispatcher'];
$app = 'dashboard';
switch( $cookie ){
    case 'registration':
    case 'beerpong':
    case 'photoshop':
        //  Just redirect to the other page
        $app = $cookie;
        break;

    default:
        // This browser has no cookie.  If the account already exists, go to the dashboard.
        // otherwise, go to registration.
        $DB = new mysqli( DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE );
        $safe_token = $DB->real_escape_string( $_REQUEST["token"] );
        $safe_pgid = $DB->real_escape_string( $_REQUEST["pgid"] );
        $query = 'SELECT accounts_pk FROM accounts WHERE token = "'.$safe_token.'" AND pgid = "' . $safe_pgid . '"';
        $account = $DB->query("SELECT * FROM accounts WHERE pgid='$safe_pgid';" );
        if( $account->num_rows ) $app = "dashboard";
        else                     $app = "registration/registration.php";
        break;
}

// Dispatch to the correct subsystem
header( 'location: /' . $app . '?pgid=' . $_REQUEST['pgid'] . '&token=' . $_REQUEST['token'] );
