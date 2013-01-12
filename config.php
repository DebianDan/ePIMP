<?php

require_once( 'common.php' );
require_once( '_before.php' );

define( 'DB_HOST', 'db.expensiparty.com' );
define( 'DB_DATABASE', 'expensiparty' );
define( 'DB_USER', 'expensiparty_dev' );
define( 'DB_PASSWORD', 'asdfASDF00' );

define( 'S3_USERNAME' , 'expensiparty_s3_user' );
define( 'S3_ACCESS_KEY_ID', 'AKIAJ4I5MTGDDJ5N2AVA' );
define( 'S3_SECRET_ACCESS_KEY', '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' );
define( 'S3_BUCKET', 'expensiparty' );

// Some input validation
if( isset( $_REQUEST['pgid'] ) && intval( $_REQUEST['pgid'] ) != $_REQUEST['pgid'] ){
    fatalErrorContactMatt( 'Invalid pgid.' );
}

if( isset( $_REQUEST['token'] ) && preg_match( '/[A-Z]{6}/', $_REQUEST['token'] ) ){
    fatalErrorContactMatt( 'Invalid Token.' );
}

if( isset( $_REQUEST['minor'] ) && $_REQUEST['minor'] != 'true' ){
    fatalErrorContactMatt( 'Invalid minor.' );
}

