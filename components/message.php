<!--
Add these:
<link href="/css/bootstrap.min.css" rel="stylesheet" media="screen">
<link href="/css/queue.css" rel="stylesheet" media="screen">

PHP:
$message = "Yeah.";
$bodycode = '<div></div>';
include 'message.php';
-->
<div style="width:100%; text-align:center; padding-top:3em;">
	<h2 style="margin-bottom:1em; padding:0 1em;"><?php echo $message; ?></h2>
	<?php echo $bodycode; ?>
</div>