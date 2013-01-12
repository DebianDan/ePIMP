<?php
require_once("user_info.php");

if ($play_mingle == 0) {
	http_redirect("./getinfo.php", array("pgid" => $pgid, "token" => $token));
}
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
  <style>

  </style>
</head>
<body>

<div data-role="page">

  <div data-role="header">
    <h1><?php echo $first_name . " " . $last_name;?></h1>
  </div><!-- /header -->

  <div data-role="content">
    <div id="points">
      <h2>Total Points</h2>
      <strong><?php echo $points?></strong>
    </div>

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
              <td>$highscore['firstname'] . ' ' . $highscore['lastname']</td>
              <td>$highscore['points']</td>
            </tr>
          }
        ?>
      </table>
    </div>


		<?php
		if ($play_mingle == 1) {
			require_once("get_friendship.php");
		?>

    <div id="mingle">
      <h2>Play Mingle</h2>
      <fieldset data-role="controlgroup">
      <legend>Find and say hello to these people! They will be looking for you as well. Open their info box and click their check box when you meet them!</legend>
      <ul data-role="listview" data-inset="true">
        <?php
          foreach($friends as $friend) {
            echo "<li><a href=\'/dashboard/userProfile.php?userID=".$pgid."&token=".$token."&friendID=".$friend['pgid'] "' data-transition='slide'>". $friend['firstname'] . ' ' . $friend['lastname'] ."</a></li>"
          }
        ?>
      </ul>
    </div>

		<?php
		}
		?>
		
  </div><!-- /content -->
  <div data-theme="a" data-role="footer" data-position="fixed">
    <h3>
        Footer - Expensify stuff can go here
    </h3>
  </div>


</div><!-- /page -->

</body>
</html>
