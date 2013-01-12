<?php
require_once( '../config.php' );

//$DB = new mysqli( DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE );

email_person( 1, 'Welcome', array( 'name' => 'Matt', 'url' => 'http://expensiparty.com/?pgid=1287316&token=FPAKJZ' ) );
die(' Done here.' );
