<?php

require_once( 'common.php' );
require_once( '_before.php' );

define( 'DB_HOST', 'db.expensiparty.com' );
define( 'DB_DATABASE', 'expensiparty' );
define( 'DB_USER', 'expensiparty_dev' );
define( 'DB_PASSWORD', 'asdfASDF00' );

// define( 'S3_USERNAME' , 'expensiparty_s3_user' );
//define( 'S3_ACCESS_KEY_ID', 'AKIAJ4I5MTGDDJ5N2AVA' );
//define( 'S3_SECRET_ACCESS_KEY', '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' );
//define( 'S3_BUCKET', 'expensiparty' );

define( 'S3_USERNAME', 'uploader' );
define( 'S3_ACCESS_KEY_ID', 'AKIAJAKXITATYBV3T4IA' );
define( 'S3_SECRET_ACCESS_KEY', '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' );
define( 'S3_BUCKET', 'expensiparty2k13' );

define( 'STARTING', '1000' );
define( 'BEER_PONG_PLAY', '100' );
define( 'BEER_PONG_WIN', '150' );
define( 'PHOTOSHOP', '100' );
define( 'MINGLE', '100' );
define( 'JOBS', '5000' );

define( 'ROULETTE_INTERVAL', '2' ); // in terms of minutes
