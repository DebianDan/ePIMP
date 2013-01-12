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

	if (mysqli_connect_errno()) {
		printf("Connect failed: %s\n", mysqli_connect_error());
		exit();
	}


	//params
	if ($_GET["pgid"] and $_GET["token"]) {

		$query = "SELECT accounts_pk FROM accounts WHERE pgid='". $_GET["pgid"] . "' AND token='" .$_GET["token"]. "';";
		//echo $query . "<br/>";
		$result =  $DB->query($query) or die($DB->error.__LINE__);
		if ($row = $result->fetch_assoc()) {
			echo $row['accounts_pk'] . "<br/>";
		}

		//in queue


		//not in queue
	}
	//no params
	else{
		//show query list

	}

	/*
	$query = "SELECT * FROM photoshop WHERE 1=1";
	$result =  $DB->query($query) or die($DB->error.__LINE__);

	echo $result->num_rows . "<br/>";

	if($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			echo $row['email'] . "<br/>";
		}
	}
	else {
		echo 'NO RESULTS';
	}
	*/


	// CLOSE CONNECTION
	mysqli_close($DB);




	?>

</body>
</html>