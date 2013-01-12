<!DOCTYPE html>
<html>
<head>
	<title>Beer Pong Queue</title>
	<?php
	if(isset($_GET['longwait']))
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
	<link href="/css/queue.css" rel="stylesheet" media="screen">

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
			$usera = $_GET["alost"];
			$userb = $_GET["blost"];
			$query = "select beer_pong_pk from beer_pong where user_a = ".$usera." and user_b = ".$userb." and (state = 1 or state = 2)";
			$result = $DB->query($query);
			$row = $result->fetch_assoc();
			$bp_pk_losing = $row['beer_pong_pk'];

			$query = "select beer_pong_pk from beer_pong where state = 1 order by beer_pong_pk asc limit 1";
			$result = $DB->query($query);

			$row = $result->fetch_assoc();
			$top_open_team = $row['beer_pong_pk'];

			$games_won = $top_open_team - $bp_pk_losing;
			//echo $games_won;

			$points = 0;
			$points = $points + $games_won * BEER_PONG_WIN;
			$points = $points + BEER_PONG_PLAY;

			$query = "insert into points(accounts_fk, points, reason, created) values(".$usera.",".$points.", 'Beer Pong', NOW())";
			$result = $DB->query($query);
			//echo $query;
			$query = "insert into points(accounts_fk, points, reason, created) values(".$userb.",".$points.", 'Beer Pong', NOW())";
			$result = $DB->query($query);


			// update the state
			$query = "update beer_pong set state = 2 where beer_pong_pk = ". $top_open_team;
			$DB->query($query);
			//echo "<BR>";
			//echo $query;


			$query = "update beer_pong set state = 0 where beer_pong_pk = ". $bp_pk_losing;
			$DB->query($query);
			//echo "<BR>";
			//echo $query;

		}

		//show query list
		$query = 'SELECT a.first_name af, a.last_name al, b.first_name bf, b.last_name bl FROM beer_pong bp JOIN accounts a ON bp.user_a = a.accounts_pk ';
		$query = $query.'JOIN accounts b ON bp.user_b = b.accounts_pk WHERE bp.state = 1 or bp.state = 2 LIMIT 5';
		
		echo $query;
		
		$result = $DB->query($query) or die($DB->error.__LINE__);


//First position is always the current winner so format it and the second player is the opponent
		//then display the top the 3 in the queue


		//$position = 1;
		$team = "";
		$array = array();
		while($row = $result->fetch_assoc()) {
			$team = "";
			//$team = $team.$position;
			$team = $team.'  '.$row['af'].' ';
			$team = $team.$row['al'].' & ';
			$team = $team.$row['bf'].' '.$row['bl'];
			array_push($array,$team);
			//$position = $position + 1;
		}
		for($i=0;$i<5;$i++){
			//echo $array[$i] . "<BR>";
		}
		$tournament = true;
		include '../../components/queue.php';
	}
	// params
	else
	{
		$safe_pgid = $DB->real_escape_string($_GET["pgid"]);
		$safe_token = $DB->real_escape_string($_GET["token"]);

		echo $safe_pgid;
		echo $safe_token;

		//find in beer pong
		$query = "(select beer_pong.state, beer_pong.beer_pong_pk from beer_pong join accounts a on a.accounts_pk = user_a where (state=1 or state = 2)";
		$query = $query."and a.pgid = '".$safe_pgid."' and a.token = '".$safe_token."')";
		$query = $query." union ";
		$query = $query."(select beer_pong.state, beer_pong.beer_pong_pk from beer_pong join accounts b on b.accounts_pk = user_b where (state=1 or state = 2)";
		$query = $query." and b.pgid = '".$safe_pgid."' and b.token = '".$safe_token."')";
		
		$result =  $DB->query($query);
		//echo "Query:" . $query . "<BR>";
		//in queue
		$row = $result->fetch_assoc();

		//echo $row['state'];
		if ($row['state'] == 1 || $row['state'] == 2)
		{
			//echo " state is 1 or 2";
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
			//echo "Winning:" . $winning . "<BR>";
			//echo "Opponent:" . $opponent . "<BR>";

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
				$bodycode = '<h3>Which Team Won?<h3>';
				$bodycode = $bodycode . '<form name="input" action="./index.php" method="get">';
				$bodycode = $bodycode . '<button type="button" onClick="location.href=\'./index.php?alost='.$o_a.'&blost='.$o_b.'\'">'.$winner_a . ' & ' .$winner_b.'</button>';
				$bodycode = $bodycode . '<button type="button" onClick="location.href=\'./index.php?alost='.$w_a.'&blost='.$w_b.'\'">'.$opponent_a . ' & ' .$opponent_b.'</button>';
				$bodycode = $bodycode . '</form>';
			}
			else
			{
				//minus the 2 players that are currently playing
				$message = "You are at position ". ($pos-2) . "<br/>";
				include '../../components/message.php';
			}
		}

		//MAKE SURE (NOT IN QUEUE) ADDING A PAIR WORKS
		// not in queue
		else
		{
			$query = "SELECT accounts_pk FROM accounts WHERE pgid='". $safe_pgid . "' AND token='" .$safe_token. "'";
			$result = $DB->query($query);
			$row = $result->fetch_assoc();
			$accounts_pk = $row['accounts_pk'];

			//echo "*".$accounts_pk."*";

			if(is_null($accounts_pk))
			{
				//echo "Need to register";
				$message = "You need to register your braclet before using the Beer Pong at the front desk!";
				include '../../components/message.php';
			}
			else
			{
			$query = "SELECT * FROM beer_pong WHERE user_b = 0 AND state = 1";
			$result = $DB->query($query);
			$row = $result->fetch_assoc();

			if ($row['state'] == 1)
			{
				$bp_pk = $row['beer_pong_pk'];
				$query = "UPDATE beer_pong SET user_b = " . $accounts_pk . " WHERE beer_pong_pk = ". $bp_pk;
				$DB->query($query);

				//find in beer pong
				$query = "(select beer_pong.state, beer_pong.beer_pong_pk from beer_pong join accounts a on a.accounts_pk = user_a where (state=1 or state = 2)";
				$query = $query."and a.pgid = '".$safe_pgid."' and a.token = '".$safe_token."')";
				$query = $query." union ";
				$query = $query."(select beer_pong.state, beer_pong.beer_pong_pk from beer_pong join accounts b on b.accounts_pk = user_b where (state=1 or state = 2)";
				$query = $query." and b.pgid = '".$safe_pgid."' and b.token = '".$safe_token."')";
	
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
					$message = "You have been added to the queue at position ". ($pos-2) . "<br/>";
					include '../../components/message.php';
				}
			}
			else
			{
				//echo $accounts_pk;
				echo "<BR>";
				$query = "SELECT COUNT(*) c FROM beer_pong WHERE state = 1 OR state = 2";
				$result = $DB->query($query);
				$row = $result->fetch_assoc();
				$count = $row['c'];
				
				if($count == 0)
					$query = "INSERT INTO beer_pong(user_a, user_b, state) VALUES (" . $accounts_pk . ",0, 2)";
				else
					$query = "INSERT INTO beer_pong(user_a, user_b, state) VALUES (" . $accounts_pk . ",0, 1)";
				
				echo $query;
				echo " Added a new user";
				$DB->query($query);
				//header("Location:./index.php?longwait=1");
			}
			}
		}
	}

	// CLOSE CONNECTION
	mysqli_close($DB);

?>

</body>
</html>