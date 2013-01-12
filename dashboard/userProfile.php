<?php
require_once("user_info.php");
?>

<!DOCTYPE html>
<html>
	<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, minimum-scale=1, maximum-scale=1">
	<title><?php $firstname . ' ' . $lastname?></title>
	<link rel="stylesheet"  href="http://code.jquery.com/mobile/1.0a4.1/jquery.mobile-1.0a4.1.min.css" />
	<link rel="stylesheet" href="../_assets/css/jqm-docs.css"/>
	<script type="text/javascript" src="http://code.jquery.com/jquery-1.5.min.js"></script>
	<script type="text/javascript" src="http://code.jquery.com/mobile/1.0a4.1/jquery.mobile-1.0a4.1.min.js"></script>
</head>
<body>

<div data-role="page" data-theme="e">

	<div data-role="header" data-theme="e">
		<h1>Ta-da!</h1>
	</div><!-- /header -->

	<div data-role="content">
    <?php echo "Dynamic content"; $_POST['param1'] ?>
		<p>That was an animated page transition effect that we added with a <code>data-transition</code> attribute on the link.</p>
		<p>Since it uses CSS transforms, this should be hardware accelerated on many mobile devices.</p>
		<p>What do you think?</p>
		<a href="docs-transitions.html" data-role="button" data-theme="b" data-rel="back">I have met this person</a>
	</div>
</div>


</body>
</html>


$.post('/getProfile.php?param1=foo')