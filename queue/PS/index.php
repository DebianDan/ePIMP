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
		//find in photo
		$query = "SELECT * FROM photoshop ps JOIN accounts a ON ps.users_fk = a.accounts_pk WHERE pgid='". $DB->real_escape_string($_GET["pgid"]) . "' AND token='" .$DB->real_escape_string($_GET["token"]). "' ORDER BY photoshop_pk DESC LIMIT 1";
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
				echo '<h3>There was a fatal error</h3>';
				echo '<p>This was embarassing for us unless you\'re being cheeky. Your best bet here is to find an Expensify employee and ask them for Matt.</p>';
				echo '<p>Helpful information: Invalid Token</p>';
				die();
			}

		}
	}
	//no params
	else{
		//show query list
		$query = 'SELECT a.first_name af, a.last_name al, FROM photoshop ps JOIN accounts a ON ps.user_fk = a.accounts_pk WHERE ps.state = 1';
		$result = $DB->query($query) or die($DB->error.__LINE__);

		$pos = 1;

		while($row = $result->fetch_assoc()) {
			echo $position.'  ';
			echo $row['af'].' ';
			echo $row['al'];
			echo "<BR>";
			$pos = $pos + 1;
		}
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