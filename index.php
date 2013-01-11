<?php
// generic registration stuff here.

if( !file_exists( 'config.php' ) ){
    die( 'Config file doesn\' exist.  Did you forget to copy config.php.default to config.php?');
}

require_once( 'config.php' );

$DB = new mysqli( DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE );


