<!DOCTYPE html>
<html>
<head>
	<title>Bootstrap 101 Template</title>
	<!-- Bootstrap -->
	<link href="/css/bootstrap.min.css" rel="stylesheet" media="screen">
</head>
<body>
	<h1>Hello, world!</h1>
	<script src="http://code.jquery.com/jquery-latest.js"></script>
	<script src="/js/bootstrap.min.js"></script>

	<?php
		// generic registration stuff here.

	if( !file_exists( '../../config.php' ) ){
		die( 'Config file doesn\' exist.  Did you forget to copy config.php.default to config.php?');
	}

		require_once( '../../config.php' );

	$DB = new mysqli( DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE );

	?>

</body>
</html>