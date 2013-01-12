<?php
require_once("user_info.php");
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
        <!--some php required-->
          <!-- <tr>
                        <td>1st</td>
                        <td>Jerry</td>
                        <td>100</td>
                    </tr>
                    <tr>
                        <td>2nd</td>
                        <td>Molly</td>
                        <td>40</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>Chuck</td>
                        <td>10</td>
                    </tr> -->
      </table>
    </div>


		<?php
		require_once("get_friendship.php");
		?>
		
    <div id="mingle">
      <h2>Play Mingle</h2>
      <fieldset data-role="controlgroup">
      <legend>Find and say hello to these people! They will be looking for you as well. Open their info box and click their check box when you meet them!</legend>

      <ul data-role="listview" data-inset="true">
        <li><a class="minglename" href="/dashboard/userProfile.php?userID=1" data-transition="slide">Acura</a></li>
        <li><a class="minglename" href="/dashboard/userProfile.php?userID=1" data-transition="slide">Audi</a></li>
        <li><a class="minglename" href="/dashboard/userProfile.php?userID=1" data-transition="slide">BMW</a></li>
        <li><a class="minglename" href="/dashboard/userProfile.php?userID=1" data-transition="slide">Cadillac</a></li>
        <li><a class="minglename" href="/dashboard/userProfile.php?userID=1" data-transition="slide">Ferrari</a></li>
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
