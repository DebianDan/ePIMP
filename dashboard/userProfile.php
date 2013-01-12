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
	<title><?php $friend['first_name'] . ' ' . $friend['last_name']?></title>
  <link rel="stylesheet" href="/css/jquery.mobile.min.css"/>
  <script src="/js/jquery-latest.js"></script>
  <script src="/js/jquery.mobile-1.2.0.min.js"></script>
</head>
<body>

<div data-role="page" data-theme="e">

	<div data-role="header" data-theme="e">
    <h1><?php $friend['first_name'] . ' ' . $friend['last_name']?></h1>
	</div><!-- /header -->

	<div data-role="content">
    <?php
      echo "<p>" . $friend['intro'] . "</p>";
      echo "<a href='update_friendship.php?pgid=" . $pgid . "&token=" . $token . "&friendID=" . $friend['pgid'] . "&mingle_status_pk=" . $_REQUEST['mingle_status_pk'] . "' datal-rel='back' data-role='button' data-ajax='false'>" . "I have met this person!" ."</a>";
    ?>
    <a href="index.php" data-role="button" data-theme="c" data-rel="back">Go Back</a>
	</div>
</div>


</body>
</html>


$.post('/getProfile.php?param1=foo')