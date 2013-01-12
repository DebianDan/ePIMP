<?php
require_once( '../config.php' );
    grantOnce( 1000, 'Made the right choice.' );
    // Redirect to the true jobs page
    header( "location: https://www.expensify.com/jobs" );

