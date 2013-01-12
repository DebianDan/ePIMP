<?php
// generic registration stuff here.

if( !file_exists( 'config.php' ) ){
    die( 'Config file doesn\' exist.  Did you forget to copy config.php.default to config.php?');
}

require_once( 'config.php' );

// Dispatch according to the cookie, if specified.  Every party tablet will have a cookie
// indicating which function it is supposed to serve.  If there is no cookie, it means the
// user has tapped on their personal device.
$cookie = isset( $_COOKIE['dispatcher'] ) ? $_COOKIE['dispatcher'] : "";
switch( $cookie ){
    case "":
    case "registration":
        // If the request is empty: no pgid or token, then no idea what they're doing.
        // Send them to the welcome.html page.
        if( !isset( $_REQUEST['token'] ) || !isset( $_REQUEST['pgid'] ) ){
            header( 'location: /welcome.html' );
            die();
        }

        // This browser has no cookie (eg, is a user's phone) or is the registration tablet.  If no
        // account, create one.  If there is an account, if we're the registration device, go back
        // to the registration screen -- otherwise to the dashboard.
        $DB = new mysqli( DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE );
        $safe_token = $DB->real_escape_string( $_REQUEST["token"] );
        $safe_pgid = $DB->real_escape_string( $_REQUEST["pgid"] );
        $account = $DB->query("SELECT * FROM accounts WHERE pgid='$safe_pgid';" );
        if( !$account->num_rows ) 
        {
            // Account doesn't exist yet -- begin registering it.
            $app = "registration/registration.php";
        }
        else
        {
            // Account does exist -- go to the dashboad if it's a user's phone,
            // otherwise back to the "tap in" screen if the registration device.
            if( $cookie=="registration" ){
                header( 'location: /registration/thankyou.html' );
                die();
            }
            else{
                $app = "dashboard";
            }
        }
        break;

    case 'queue/BP':
    case 'queue/PS':
        //  Just redirect to the specific sub-app
        $app = $cookie;
        break;

    default:
        echo "Invalid dispatcher cookie";
        exit;
}

// Dispatch to the correct subsystem
header( 'location: /' . $app . '?pgid=' . $_REQUEST['pgid'] . '&token=' . $_REQUEST['token'] );
