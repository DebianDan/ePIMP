<!--
Add these:
<link href="/css/queue.css" rel="stylesheet" media="screen">
<link href="/css/bootstrap.min.css" rel="stylesheet" media="screen">
<script src="http://code.jquery.com/jquery-latest.js"></script>
<script src="/js/bootstrap.min.js"></script>

PHP:
$array = array("foo", "bar", "bar", "foo", "fubar");
$tournament = true; // for champion/challenger identifier, ignore for standard queue
include 'queue.php';
-->
<div class="container-fluid queue <?php
		if (isset($tournament) && $tournament) {
			echo 'tournament';
		}
	?>">
	<div class="row-fluid">
		<div class="span6 guide">
			<img src="/img/wrist_v.png" />
		</div>
		<div class="span6">
			<?php
				$length = count($array);
				if ($length == 0) {
					echo '<div class="row-fluid"><div class="span12 entry">Queue is empty.</div></div>';
				} else {
					for ($i = 0; $i < $length; $i++) {
						$class = 'row-fluid';
						if (isset($tournament) && $tournament) {
							if ($i == 0){
								$class = $class.' champion';
							} else if ($i == 1){
								$class = $class.' challenger';
							}
						}
						echo '<div class="'.$class.'"><div class="span12 entry">'.$array[$i].'</div></div>';
					}
				}
			?>
			
		</div>
	</div>
</div>