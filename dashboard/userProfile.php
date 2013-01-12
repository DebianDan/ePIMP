<?php
require_once("user_info.php");

$friendID = $_REQUEST['friendID'];
$friend = get_user_info($friendID);
?>

<!DOCTYPE html>
<html>
	<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, minimum-scale=1, maximum-scale=1">
	<title><?php echo $friend['first_name'] . ' ' . $friend['last_name'];?></title>
  <link rel="stylesheet" href="/css/jquery.mobile.min.css"/>
  <script src="/js/jquery-latest.js"></script>
  <script src="/js/jquery.mobile-1.2.0.min.js"></script>
</head>
<body>

<div data-role="page" data-theme="e">

	<div data-role="header" data-theme="e">
    <h1><?php echo $friend['first_name'] . ' ' . $friend['last_name'];?></h1>
	</div><!-- /header -->

	<div data-role="content">
    <?php
      echo "<p>" . $friend['intro'] . "</p>";
      echo "<button id='addfriend' data-icon='check'>i have met this person</button>";
      echo "<a href='index.php?pgid=" . $pgid . "&token=" . $token . "' data-role='button' data-icon='arrow-l'>Go Back</a>";
    ?>
	  <p id="messages">
    </p>
  </div>
</div>
<script>
$(function() {
  $('#addfriend').click(function(){
    $('#addfriend').parent().remove();
    $('#messages').text('Congratulations! Make sure your new friend clicks your name right away, or else you won\'t be awarded Bling.');
    console.log('sending data...');
    <?php
    //use php to render part of the script
      echo "var data = 'pgid=" . $pgid . "&token=" . $token . "&friendID=" . $friend['pgid'] . "&mingle_status_pk=" . $_REQUEST['mingle_status_pk'] . "'";
      echo "\n";
    ?>
    $.get('update_friendship.php?'+data, function(res) {});
  })
});
</script>

</body>
</html>

