<!DOCTYPE html>
<html>
<head>
	<title>Bootstrap 101 Template</title>
	<!-- Bootstrap -->
	<link href="/css/bootstrap.min.css" rel="stylesheet" media="screen">
	<meta http-equiv="refresh" content="5;URL='./index.php'">
</head>
<body>
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
				$pos = 1;
				while($row = $result->fetch_assoc()){
					if($row['users_fk'] == $accounts_pk){
						//$pos = $row['photoshop_pk'] - $first + 1;
						break;
					}
					$pos = $pos + 1;
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
				echo '<h3>There was an error</h3>';
				echo '<p>You need to register your braclet before using the Photoshop!</p>';
				echo '<p>Helpful information: Not Currently Registered</p>';
				die();
			}

		}
	}
	//no params
	else{
		//show query list
		$query = 'SELECT a.first_name f, a.last_name l FROM photoshop ps JOIN accounts a ON ps.users_fk = a.accounts_pk WHERE ps.state=1 LIMIT 5';
		$result = $DB->query($query) or die($DB->error.__LINE__);

		$pos = 1;

		//array holds the a string of the top 5 users on in queue
		$array = array();
		while($row = $result->fetch_assoc()) {
			$line = "";
			$line = $pos.'  ';
			$line = $line . $row['f'].' ';
			$line = $line . $row['l'];
			$line = $line . "<BR>";
			array_push($array, $line);
			$pos = $pos + 1;
		}
	}


	// CLOSE CONNECTION
	mysqli_close($DB);




	?>

</body>
</html>