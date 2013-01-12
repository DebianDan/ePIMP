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
		//find in beer pong
		$query = "SELECT * FROM beerpong bp JOIN accounts a ON ps.users_fk = a.accounts_pk WHERE pgid='". $DB->real_escape_string($_GET["pgid"]) . "' AND token='" .$DB->real_escape_string($_GET["token"]). "' ORDER BY photoshop_pk DESC LIMIT 1";
		$result =  $DB->query($query);
		//in queue
		$row = $result->fetch_assoc();
		if ($row['state'] == 1) {
			$accounts_pk = $row['accounts_pk'];
			$query = "SELECT photoshop_pk, users_fk FROM photoshop WHERE state=1 ORDER BY photoshop_pk ASC";
			$result = $DB->query($query);
			$row = $result->fetch_assoc();
			$first = $row['photoshop_pk'];
			if ($accounts_pk == $row['users_fk']) {
				$pos = 1;
			}
			else{
				while($row = $result->fetch_assoc()){
					if($row['users_fk'] == $accounts_pk){
						$pos = $row['photoshop_pk'] - $first + 1;
						break;
					}
				}
			}
			echo "You are Position ". $pos . "<br/>";
		}
		//not in queue
		else{
			$query = "SELECT accounts_pk FROM accounts WHERE pgid='". $DB->real_escape_string($_GET["pgid"]) . "' AND token='" .$DB->real_escape_string($_GET["token"]). "'";
			$result = $DB->query($query);
			$row = $result->fetch_assoc();
			$accounts_pk = $row['accounts_pk'];
			$query = "INSERT INTO photoshop (users_fk, state) VALUES (" . $accounts_pk . ", 1)";
			$DB->query($query);

			$query = "SELECT photoshop_pk, users_fk FROM photoshop WHERE state=1 ORDER BY photoshop_pk ASC";
			$result = $DB->query($query);
			$row = $result->fetch_assoc();
			$first = $row['photoshop_pk'];
			while($row = $result->fetch_assoc()){
				if($row['users_fk'] == $accounts_pk){
					$pos = $row['photoshop_pk'] - $first + 1;
					break;
				}
			}
			if ($pos != null) {
				echo "You have been added to the queue.  You are Position ". $pos;
			}
			else{
				//shouldn't get here unless tampering with pgid and token
				echo "You have entered an invalid pgid or token!";
			}

		}
		
	}

	// CLOSE CONNECTION
	mysqli_close($DB);

?>

</body>
</html>