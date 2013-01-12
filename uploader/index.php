<!DOCTYPE html>
<html>
<head>
	<title>Bootstrap 101 Template</title>
	<!-- Bootstrap -->
	<link href="/css/bootstrap.min.css" rel="stylesheet" media="screen">
</head>
<body>
	<?php
		if (isset($_FILES["file"])){
			if ($_FILES["file"]["error"] > 0){
				echo "Error: " . $_FILES["file"]["error"] . "<br>";
			} else {
				echo "Upload: " . $_FILES["file"]["name"] . "<br>";
				echo "Type: " . $_FILES["file"]["type"] . "<br>";
				echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
				echo "Stored in: " . $_FILES["file"]["tmp_name"];
			}
		}
	?>
	<div class="container-fluid">
		<div class="row-fluid">
			<div class="span4">
				<!--Sidebar content-->
				<form enctype="multipart/form-data" action="index.php" method="post">
					<input type="file" name="file" />
					<input type="submit" value="Upload" />
				</form>
			</div>
			<div class="span8">
				<!--Body content-->
			</div>
		</div>
    </div>
	<script src="http://code.jquery.com/jquery-latest.js"></script>
	<script src="/js/bootstrap.min.js"></script>
</body>
</html>