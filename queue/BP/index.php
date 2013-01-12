<!DOCTYPE html>
<html>
<head>
	<title>Bootstrap 101 Template</title>
	<?php
	if(isset($_GET['ten']))
	{
		echo "<meta http-equiv='refresh' content='20;url=./index.php'>";
	}
	else
	{
		echo "<meta http-equiv='refresh' content='5;url=./index.php'>";
	}
	?>
	<!-- Bootstrap -->
	<link href="/css/bootstrap.min.css" rel="stylesheet" media="screen">
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
	//no params
	if (!isset($_GET["pgid"]) and !isset($_GET["token"]))
	{
		$query = "DELETE FROM beer_pong WHERE user_b = 0";
		$DB->query($query) or die($DB->error.__LINE__);

		//show query list
		$query = 'SELECT a.first_name af, a.last_name al, b.first_name bf, b.last_name bl FROM beer_pong bp JOIN accounts a ON bp.user_a = a.accounts_pk ';
		$query = $query.'JOIN accounts b ON bp.user_b = b.accounts_pk WHERE bp.state = 1 LIMIT 5';
		
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
	else
	{
		$safe_pgid = $DB->real_escape_string($_GET["pgid"]);
		$safe_token = $DB->real_escape_string($_GET["token"]);

		//find in beer pong
		$query = "select beer_pong.state, beer_pong.beer_pong_pk from beer_pong inner join accounts a on a.accounts_pk = user_a inner join accounts b on b.accounts_pk = user_b where ";
		$query = $query."state = 1 and ( ( a.pgid = '".$safe_pgid."' and a.token = '".$safe_token."' ) OR (b.pgid = '".$safe_pgid."' and b.token = '".$safe_token."' ) )";
		$result =  $DB->query($query);
		//in queue
		$row = $result->fetch_assoc();
		if ($row['state'] == 1) 
		{
			$bp_pk = $row['beer_pong_pk'];
			$query = "SELECT beer_pong_pk FROM beer_pong WHERE state=1 ORDER BY beer_pong_pk ASC";
			$result = $DB->query($query);
			$pos = 0;
				
			while($row = $result->fetch_assoc()){
				$first = $row['beer_pong_pk'];
				$pos = $pos + 1;
				if($first == $bp_pk)
				{
					break;
				}
			}
			echo "You are at position ". $pos . "<br/>";
		}
		// not in queue
		// test data ASDFEW  12345
		else
		{
			$query = "SELECT accounts_pk FROM accounts WHERE pgid='". $DB->real_escape_string($_GET["pgid"]) . "' AND token='" .$DB->real_escape_string($_GET["token"]). "'";
			$result = $DB->query($query);
			$row = $result->fetch_assoc();
			$accounts_pk = $row['accounts_pk'];
			$query = "SELECT * FROM beer_pong WHERE user_b = 0 AND state = 1";
			$result = $DB->query($query);
			$row = $result->fetch_assoc();
			
			if ($row['state'] == 1) 
			{	
				$bp_pk = $row['beer_pong_pk'];
				$query = "UPDATE beer_pong SET user_b = " . $accounts_pk . " WHERE beer_pong_pk = ". $bp_pk;
				$DB->query($query);
				
				//find in beer pong
				$query = "select beer_pong.state, beer_pong.beer_pong_pk from beer_pong inner join accounts a on a.accounts_pk = user_a inner join accounts b on b.accounts_pk = user_b where ";
				$query = $query."state = 1 and ( ( a.pgid = '".$safe_pgid."' and a.token = '".$safe_token."' ) OR (b.pgid = '".$safe_pgid."' and b.token = '".$safe_token."' ) )";
				$result =  $DB->query($query);
				//in queue
				$row = $result->fetch_assoc();
				if ($row['state'] == 1) 
				{
					$bp_pk = $row['beer_pong_pk'];
					$query = "SELECT beer_pong_pk FROM beer_pong WHERE state=1 ORDER BY beer_pong_pk ASC";
					$result = $DB->query($query);
					$pos = 0;
				
					while($row = $result->fetch_assoc()){
						$first = $row['beer_pong_pk'];
						$pos = $pos + 1;
						if($first == $bp_pk)
						{
							break;
						}	
					}
					echo "You have been added to the queue at position ". $pos . "<br/>";
				}
			}
			else
			{
				$query = "INSERT INTO beer_pong(user_a, user_b, state) VALUES (" . $accounts_pk . ",0, 1)";
				echo " Added a new user";
				$DB->query($query);
				header("Location:/index.php?ten=1");

			}
		}
	}

	// CLOSE CONNECTION
	mysqli_close($DB);

?>

</body>
</html>