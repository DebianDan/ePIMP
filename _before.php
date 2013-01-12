<?php

// Some basic validation
if( isset( $_REQUEST['pgid'] ) && preg_match( '/^[0-9]+$/', $_REQUEST['pgid'] ) != 1 ){
    fatalErrorContactMatt( 'Invalid pgid.' );
}

if( isset( $_REQUEST['token'] ) && preg_match( '/^[A-Z]{6}$/', $_REQUEST['token'] ) != 1 ){
    fatalErrorContactMatt( 'Invalid Token.' );
}

if( isset( $_REQUEST['minor'] ) && $_REQUEST['minor'] != 'true' ){
    fatalErrorContactMatt( 'Invalid minor.' );
}
