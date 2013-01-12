<?php
require_once("user_info.php");

if ($play_mingle == 0) {
  header("Location:./getinfo.php?". $_SERVER['QUERY_STRING']);
  die();
}

?>

<!DOCTYPE html>
<html>
<head>
  <title>User Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="/css/jquery.mobile.min.css"/>
  <style>
    td {
      font-size: 60px;
    }
  </style>
</head>
<body>

<div data-role="page">

  <div data-role="header">
    <h1><?php echo $first_name . " " . $last_name;?></h1>
  </div><!-- /header -->

  <div data-role="content">
      <table data-role="table" id="points" data-mode="reflow">
        <thead>
          <tr>
            <th>Total Points</th>
            <th>Ranking</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <?php
              $points = get_total_points($pgid, $accounts_pk);
              $ranking = 2
              echo "<td>".$points."</td>";
              echo "<td>".$ranking."</td>";
            ?>
          </tr>
          </tbody>
      </table>
      <ul data-role="listview" data-inset="true">
        <li>
          <a href='/dashboard/about_bling.php' data-transition='slide' data-ajax='false'>What is Bling?</a>
        </li>
      </ul>

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
            <?php
              $beerpong_pos = 100;
              $photoshop_pos = 4;
              echo "<td>".$beerpong_pos."</td>";
              echo "<td>".$photoshop_pos."</td>";
            ?>
          </tr>
          </tbody>
      </table>
    </div>

    <div id="leaderboard">
      <h2>Leaderboard</h2>
      <table>
        <?php
          // foreach($highscores as $highscore) {
          //   echo "<tr>";
          //   echo  "<td>" . $highscore['rank'] . "</td>";
          //   echo  "<td>" . $highscore['firstname'] . ' ' . $highscore['lastname'] . "</td>";
          //   echo  "<td>" . $highscore['points'] . "</td>";
          //   echo "</tr>";
          // }
        ?>
      </table>
    </div>


    <?php
    if ($play_mingle == 1) {
      require_once("get_friendship.php");
      global $friends;
    ?>

    <div id="mingle">
      <h2>Play Mingle</h2>
      <fieldset data-role="controlgroup">
      <legend>Find and say hello to these people! They will be looking for you as well. Open their info box and click the "I met them!" button after you meet them!</legend>
      <ul data-role="listview" data-inset="true">
        <?php
          if (is_array($friends))
          {
            foreach($friends as $friend) {
              echo "<li>";
              echo "<a href='/dashboard/userProfile.php?pgid=" . $pgid . "&token=" . $token . "&friendID=" . $friend['pgid'] . "&mingle_status_pk=" . $friend['mingle_status_pk'] . "' data-transition='slide' data-ajax='false'>" . $friend['first_name'] . ' ' . $friend['last_name'] ."</a>";
              echo "</li>";
            }
          }
        ?>
      </ul>
    </div>

    <?php
      }//end bracket for the stuff up top
    ?>
    <div id="roulette">
      <ul data-role="listview" data-inset="true">
        <li>
          <?php
            $account = get_user_info($pgid);
            echo "<a href='/dashboard/roulette.html?pgid=" . $pgid . "&token=" . $token . "&pkid=".$account['accounts_pk']."' data-transition='slide' data-ajax='false'>Play Roulette</a>";
          ?>
        </li>
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
<script src="/js/jquery-latest.js"></script>
<script src="/js/jquery.mobile-1.2.0.min.js"></script>
</html>