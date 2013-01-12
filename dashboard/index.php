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
    table { width:95%; border-collapse:collapse; border: 1px solid white; }
    table caption { text-align:left;  }
    table thead th { text-align:left; border-bottom-width:1px; border-top-width:1px; font-weight: bold; color: #363636; }
    table th, td { text-align:left; padding:6px; border: 1px solid white;} 
    </style>
</head>
<body>

<div data-role="page">

  <div data-role="header">
    <h1><?php echo $first_name . " " . $last_name;?></h1>
  </div><!-- /header -->

  <div data-role="content">
      <table data-role="table" class="" id="points" data-mode="reflow">
        <thead>
          <tr>
            <th>Your Points</th>
            <th>Your Rank</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <?php
              //$points = get_total_points($pgid, $accounts_pk);
              //11th row has own rankings
              require_once('rank.php');
              $ranks = get_ranks($pgid);
              $n = count($ranks) -1;
              echo "<td>".$ranks[$n]['points']."</td>";
              echo "<td>".$ranks[$n]['rank']."</td>";
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
      <h2>Queue Area</h2>
      <?php
        //get access to line queue
        $bppos = getBeerpongPosition($pgid, $token);
        $pspos = getPhotoshopPosition( $pgid, $token);
        if ($bppos > 0) {
          if ($bppos == 1) $bbpos = "1st";
          if ($bppos == 2) $bbpos = "2nd";
          if ($bppos == 3) $bbpos = "3rd";
          if ($bppos > 3) $bppos = strval($bppos) . "th";
          echo "<p>You are " . $bppos . " in line for Beer Pong.</p>";
        } else if ($bppos == 0) {
          echo "<p>You are currently playing beer pong</p>";
        }
        if ($pspos > 0) {
          if ($pspos == 1) $pspos  = "1st";
          if ($pspos == 2) $pspos  = "2nd";
          if ($pspos == 3) $pspos  = "3rd";
          if ($pspos > 3)  $pspos  = strval($pspos) . "th";
          echo "<p>You are " . $pspos . " in line for Photoshop.</p>";
        } else if ($pspos == 0 ) {
          echo "<p>You are currently playing photoshop</p>";
        }
        if ($pspos == -1 && $bppos == -1) {
          echo "<p>You are not playing any game! Go sign up with an NFC-enabled device!</p>";
        }

      ?>
    </div>

    <div id="leaderboard">
      <h2>Leaderboard</h2>
      <table data-role="table" data-mode="reflow" class="movie-list table-stroke ui-table ui-table-reflow">
        <thead>
            <tr>
            <td class="ttle"><b>Rank</b></td>
            <td class="ttle"><b>User</b></td>
            <td class="ttle"><b>Score</b></td>
            </tr>
        </thead>
        <tbody>
          <?php
            for ($i=0; $i<$n; $i++)
            {
              echo "<tr>";
              echo  "<td class='ui-table-cell-label'>" . $ranks[$i]['rank'] . "</td>";
              echo  "<td class='ui-table-cell-label'>" . $ranks[$i]['first_name'] . ' ' . $ranks[$i]['last_name'] . "</td>";
              echo  "<td class='ui-table-cell-label'>" . $ranks[$i]['points'] . "</td>";
              echo "</tr>";
            }
          ?>
        </tbody>
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
      <legend>Find and say hello to these people! They will be looking for you as well. Open their info box and click the "I have met this person!" button after you meet them!</legend>
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
      <h2>Play Roulette</h2>
      <ul data-role="listview" data-inset="true">
        <li>
          <?php
            $account = get_user_info($pgid);
            echo "<a href='/dashboard/roulette.html?pgid=" . $pgid . "&token=" . $token . "&pkid=".$account['accounts_pk']."' data-transition='slide' data-ajax='false'>Play Roulette</a>";
          ?>
        </li>
      </ul>
    </div> 
<br /><br />
      <div data-theme="a" data-role="footer" data-position="fixed">
        <h3>
            ExpensiParty v3.7.2 <br />
            <a target="_blank" href="http://blog.expensify.com">blog</a> | <a target="_blank" href="jobs.php?pgid=<?= $_REQUEST['pgid'] ?>&token=<?= $_REQUEST['token'] ?>">jobs</a>
        </h3>
      </div>
  </div><!-- /content -->
</div><!-- /page -->

<script src="/js/jquery-latest.js"></script>
<script src="/js/jquery.mobile-1.2.0.min.js"></script>
</body>
</html>
