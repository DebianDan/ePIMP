<?php
require_once("../config.php");
$DB = new mysqli( DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE );

//get params
$token_id = $_GET["token"];
$pgid = $_GET["pgid"];

$query = 'SELECT first_name, last_name FROM accounts WHERE pgid = ' . $pgid;
$result = $DB->query($query);
$row = $result->fetch_assoc();
$phone_number = $row['phone_number'];


$safe_token = $DB->real_escape_string($token_id);
$safe_pgid = $DB->real_escape_string($pgid);

$query = 'SELECT accounts_pk FROM accounts WHERE token = "'.$safe_token.'" AND pgid = "' . $safe_pgid . '"';

function is_open($pgid, $mingle)
	{
		// open friendship
		if ($mingle["status"] == 0)
			return true;

		// friendship waiting for confirmation
		$curtime = gettimeofday(true);
		if ($mingle["status"] < 3 && $curtime - $mingle["time"] <= 61) {
			if ($pgid == $mingle["user_a"] && $mingle["status"] == 2)
				return true;
			if ($pgid == $mingle["user_b"] && $mingle["status"] == 1)
				return true;
		}

		// non-open
		return false;
	}

require_once("../config.php");
$con = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD) or
    die("Could not connect: " . mysql_error());
mysql_select_db(DB_DATABASE, $con);


// Get all of the mingles involve current user.
$pgid = $_REQUEST["pgid"];
$result = mysql_query( "SELECT * FROM mingle_status WHERE user_a=" . $pgid . " OR user_b=" . $pgid);
// $friends is what you want
while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
	// eliminate non-open friendship
	if (is_open($pgid, $row))
		continue;

	if ($row["user_a"] == $pgid)
		$friend_id = $row["user_b"];
	else
		$friend_id = $row["user_a"];

	$frind_result = mysql_query("SELECT first_name, last_name, intro FROM accounts WHERE pgid=" . $friend_id);
	$friend_info = mysql_fetch_array($frind_result, MYSQL_ASSOC);
	$friends[] = array("pk" => $row["mingle_status_pk"], "pgid" => $friend_id, "first_name" => $friend_info["first_name"], "last_name" => $friend_info["last_name"], "info" => $friend_info["intro"]);
}

mysql_close($con);
?>


<!DOCTYPE html>
<html>
<head>
  <title>User Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="//code.jquery.com/mobile/1.2.0/jquery.mobile-1.2.0.min.css"/>
  <script src="//code.jquery.com/jquery-1.8.2.min.js"></script>
  <script src="//code.jquery.com/mobile/1.2.0/jquery.mobile-1.2.0.min.js"></script>
  <script src="/dashboard/mingle.js"></script>

</head>
<body>

<div data-role="page">

  <div data-role="header">
    <h1><?php echo $token_id;?></h1>
  </div><!-- /header -->

  <div data-role="content">
    <div id="queue_positions">
      <h2>Line Position</h2>
      <table data-role="table" id="queue_positions" data-mode="reflow">
        <thead>
          <tr>
            <th>Beer Pong Line</th>
            <th>Photoshop Line</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><?php echo $beerpong_pos?></td>
            <td><?php echo $photoshop_pos?></td>
          </tr>
          </tbody>
      </table>
    </div>

    <div id="leaderboard">
      <h2>Leaderboard</h2>
      <table>
        <?php
          foreach($highscores as $highscore) {
            <tr>
              <td>$highscore['rank']</td>
              <td>$highscore['firstname'] . $highscore['lastname']</td>
              <td>$highscore['points']</td>
            </tr>
          }
        ?>
      </table>
    </div>

    <div id="mingle">
      <h2>Play Mingle</h2>
      <fieldset data-role="controlgroup">
      <legend>Find and say hello to these people! They will be looking for you as well. Open their info box and click their check box when you meet them!</legend>
      <ul data-role="listview" data-inset="true">
        <?php
          foreach($friends as $friend) {
            <li>
              <a href="/dashboard/userProfile.php?userID="+$currentUser['pgid']+"&token="+$token+"&friendID="+$friend['pgid'] data-transition="slide">$friend['firstname'] . $friend['lastname']</a>
            </li>
          }
        ?>
      </ul>
    </div>

  </div><!-- /content -->
  <div data-theme="a" data-role="footer" data-position="fixed">
    <h3>
        Footer - Expensify stuff can go here
    </h3>
  </div>


</div><!-- /page -->

</body>
</html>
