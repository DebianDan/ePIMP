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
	//no params
	if (!isset($_GET["pgid"]) and !isset($_GET["token"]))
	{
		//show query list
		$query = 'SELECT a.first_name af, a.last_name al, b.first_name bf, b.last_name bl FROM beer_pong bp JOIN accounts a ON bp.user_a = a.accounts_pk JOIN accounts b ON bp.user_b = b.accounts_pk WHERE bp.state = 1';
		$result = $DB->query($query) or die($DB->error.__LINE__);

		$position = 1;

		while($row = $result->fetch_assoc()) {
			echo $position.'  ';
			echo $row['af'].' ';
			echo $row['al'].' & ';
			echo $row['bf'].' ';
			echo $row['bl'];
			echo "<BR>";
			$position = $position + 1;
		}
	}
	
	// params
	else{
		$query = "SELECT accounts_pk FROM accounts WHERE pgid='". $_GET["pgid"] . "' AND token='" .$_GET["token"]. "';";
		//echo $query . "<br/>";
		$result =  $DB->query($query) or die($DB->error.__LINE__);
		if ($row = $result->fetch_assoc()) {
			echo $row['accounts_pk'] . "<br/>";
		}

		//in queue
		

		//not in queue
			
	}
*/
	// CLOSE CONNECTION
	mysqli_close($DB);

?>

</body>
</html>