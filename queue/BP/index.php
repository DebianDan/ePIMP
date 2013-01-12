<!DOCTYPE html>
<html>
<head>
	<title>Bootstrap 101 Template</title>
	<?php
	if(isset($_GET['longwait']))
	{
		echo "<meta http-equiv='refresh' content='20;url=./index.php'>";
	}
	else
	{
		//echo "<meta http-equiv='refresh' content='5;url=./index.php'>";
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

	if( !file_exists( '../../config.php' ) )
	{
		die( 'Config file doesn\' exist.  Did you forget to copy config.php.default to config.php?');
	}

	require_once( '../../config.php' );

	$DB = new mysqli( DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE );

	if (mysqli_connect_errno()) {
		printf("Connect failed: %s\n", mysqli_connect_error());
		exit();
	}
	//no params (no user braclet contact)
	if (!isset($_GET["pgid"]) and !isset($_GET["token"]))
	{
		//only delete after the longer timeout
		if (!isset($_GET["longwait"])){
			//delete user if page refreshes without a partner
			$query = "DELETE FROM beer_pong WHERE user_b = 0";
			$DB->query($query) or die($DB->error.__LINE__);
		}


		if (isset($_GET["alost"]) and isset($_GET["blost"])) {
			//HANDLE THE LOST AND WIN POINTS CASES

		}

		//show query list
		$query = 'SELECT a.first_name af, a.last_name al, b.first_name bf, b.last_name bl FROM beer_pong bp JOIN accounts a ON bp.user_a = a.accounts_pk ';
		$query = $query.'JOIN accounts b ON bp.user_b = b.accounts_pk WHERE bp.state = 1 or bp.state = 2 LIMIT 5';

		$result = $DB->query($query) or die($DB->error.__LINE__);


//First position is always the current winner so format it and the second player is the opponent
		//then display the top the 3 in the queue


		$position = 1;
		$team = "";
		$array = array();
		while($row = $result->fetch_assoc()) {
			$team = "";
			$team = $team.$position;
			$team = $team.'  '.$row['af'].' ';
			$team = $team.$row['al'].' & ';
			$team = $team.$row['bf'].' '.$row['bl'];
			array_push($array,$team);
			$position = $position + 1;
		}
		for($i=0;$i<5;$i++){
			echo $array[$i] . "<BR>";
		}

	}
	// params
	else
	{
		$safe_pgid = $DB->real_escape_string($_GET["pgid"]);
		$safe_token = $DB->real_escape_string($_GET["token"]);

	//find in beer pong
		$query = "select beer_pong.state, beer_pong.beer_pong_pk from beer_pong inner join accounts a on a.accounts_pk = user_a inner join accounts b on b.accounts_pk = user_b where ";
		$query = $query."(state = 1 or state =2) and ( ( a.pgid = '".$safe_pgid."' and a.token = '".$safe_token."' ) OR (b.pgid = '".$safe_pgid."' and b.token = '".$safe_token."' ) )";
		$result =  $DB->query($query);
echo "Query:" . $query . "<BR>";
	//in queue
		$row = $result->fetch_assoc();

		if ($row['state'] == 1 || $row['state'] == 2)
		{
			$bp_pk = $row['beer_pong_pk'];
			$query = "SELECT beer_pong_pk FROM beer_pong WHERE state = 2 OR state = 1 ORDER BY beer_pong_pk ASC";
			$result = $DB->query($query);
			$pos = 0;

			while($row = $result->fetch_assoc())
			{
				if($pos == 0){
					$winning = $row['beer_pong_pk'];
					if ($row = $result->fetch_assoc()) {
						$opponent = $row['beer_pong_pk'];
					}
				}
				$pos = $pos + 1;
				if($winning == $bp_pk || $opponent == $bp_pk)
				{
					break;
				}
			}
echo "Winning:" . $winning . "<BR>";
echo "Opponent:" . $opponent . "<BR>";

			// They are currently playing
			if($bp_pk == $winning || $bp_pk == $opponent)
			{
				// get the pk_ids from the database.
				// get the 2nd team from list - losing team
				$query = "select a.accounts_pk apk, a.first_name af, a.last_name al, b.accounts_pk bpk, b.first_name bf,b.last_name bl from beer_pong bp inner join accounts a on a.accounts_pk = bp.user_a inner join accounts b on b.accounts_pk = bp.user_b where ";
				$query = $query."beer_pong_pk = ".$winning;
				$result = $DB->query($query);
				$row = $result->fetch_assoc();
				$winner_a = $row['af'].' '.$row['al'];
				$winner_b = $row['bf'].' '.$row['bl'];
				$w_a = $row['apk'];
				$w_b = $row['bpk'];

				$query = "select a.accounts_pk apk, a.first_name af, a.last_name al, b.accounts_pk bpk, b.first_name bf,b.last_name bl from beer_pong bp inner join accounts a on a.accounts_pk = bp.user_a inner join accounts b on b.accounts_pk = bp.user_b where ";
				$query = $query."beer_pong_pk = ".$opponent;
				$result = $DB->query($query);
				$row = $result->fetch_assoc();
				$opponent_a = $row['af'].' '.$row['al'];
				$opponent_b = $row['bf'].' '.$row['bl'];
				$o_a = $row['apk'];
				$o_b = $row['bpk'];

				// print page with buttons
				echo '<h3>Which Team Won?<h3>';
				echo '<form name="input" action="html_form_action.asp" method="get">';
				echo '<button type="button" onClick="location.href=\'./index.php?alost='.$o_a.'&blost='.$o_b.'\'">'.$winner_a . ' & ' .$winner_b.'</button>';
				echo '<button type="button" onClick="location.href=\'./index.php?alost='.$w_a.'&blost='.$w_b.'\'">'.$opponent_a . ' & ' .$opponent_b.'</button>';
				echo '</form>';
			}
			else
			{
				//minus the 2 players that are currently playing
				echo "You are at position ". ($pos-2) . "<br/>";
			}
		}

		//MAKE SURE (NOT IN QUEUE) ADDING A PAIR WORKS
	// not in queue
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
				header("Location:./index.php?longwait=1");

			}
		}
	}

	// CLOSE CONNECTION
	mysqli_close($DB);

?>

</body>
</html>