<!--
Add these:
<link href="/css/queue.css" rel="stylesheet" media="screen">
<link href="/css/bootstrap.min.css" rel="stylesheet" media="screen">
<script src="http://code.jquery.com/jquery-latest.js"></script>
<script src="/js/bootstrap.min.js"></script>

PHP:
$array = array("foo", "bar", "bar", "foo", "fubar");
include 'queue.php';
-->	
<div class="container-fluid queue">
	<div class="row-fluid">
		<div class="span6 guide">
			<img src="../img/wrist_v.png" />
		</div>
		<div class="span6">
			<?php
				foreach ($array as $value) {
					echo '<div class="row-fluid"><div class="span12 entry">'.$value.'</div></div>';
				}
			?>
			
		</div>
	</div>
</div>