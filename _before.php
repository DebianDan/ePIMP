<?php

if( isset( $_REQUEST['pgid'] ) && intval( $_REQUEST['pgid'] ) != $_REQUEST['pgid'] ){
    fatalErrorContactMatt( 'Invalid pgid.' );
}

if( isset( $_REQUEST['token'] ) && preg_match( '/[A-Z]{6}/', $_REQUEST['token'] ) != 1 ){
    fatalErrorContactMatt( 'Invalid Token.' );
}

if( isset( $_REQUEST['minor'] ) && $_REQUEST['minor'] != 'true' ){
    fatalErrorContactMatt( 'Invalid minor.' );
}
