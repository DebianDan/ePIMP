<?php
// generic registration stuff here.

if( !file_exists( 'config.php' ) ){
    die( 'Config file doesn\' exist.  Did you forget to copy config.php.default to config.php?');
}

require_once( 'config.php' );

//$DB = new mysqli( DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE );

//email_person( 1, 'Welcome', array( 'name' => 'Matt', 'url' => 'http://expensiparty.com/?pgid=1287316&token=FPAKJZ' ) );
//die(' Done here.' );
$cookie = $_REQUEST['dispatcher'];
$app = 'dashboard';

switch( $cookie ){
    case 'registration':
        $app = $cookie;
        break;
}

header( 'location: /' . $app . '/?pgid=' . $_REQUEST['pgid'] . '&token=' . $_REQUEST['token'] );
