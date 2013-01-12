<!DOCTYPE html>
<html>
<head>
	<title>Photoshop Queue</title>
	<!-- Bootstrap -->
	<link href="/css/bootstrap.min.css" rel="stylesheet" media="screen">
	<link href="/css/queue.css" rel="stylesheet" media="screen">
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
	if (isset($_GET["pgid"]) and isset($_GET["token"])) {
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
						$pos = $pos + 1;
						break;
					}
					$pos = $pos + 1;
				}
			}
			$message = "You are at position ". $pos;
			include '../../components/message.php';
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
					$pos = $row['photoshop_pk'] - $first;
					break;
				}
			}
			if ($pos != null) {
				$message = "You have been added to the queue.  You are Position ". $pos;
				include '../../components/message.php';
			}
			else{
				//if you aren't registered
				$message = "You need to register your braclet before using the Photoshop!";
				include '../../components/message.php';
			}

		}
	}
	//no params
	else{
		//show query list
		$query = 'SELECT a.first_name f, a.last_name l FROM photoshop ps JOIN accounts a ON ps.users_fk = a.accounts_pk WHERE ps.state=1 LIMIT 5';
		$result = $DB->query($query) or die($DB->error.__LINE__);

		//$pos = 1;

		//array holds the a string of the top 5 users on in queue
		$array = array();
		while($row = $result->fetch_assoc()) {
			$line = "";
			//$line = $pos.'  ';
			$line = $line . $row['f'].' ';
			$line = $line . $row['l'];
			$line = $line . "<BR>";
			array_push($array, $line);
			//$pos = $pos + 1;
		}
		include '../../components/queue.php';
	}


	// CLOSE CONNECTION
	mysqli_close($DB);




	?>

</body>
</html>