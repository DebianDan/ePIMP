<!DOCTYPE html>
<html>
<head>
	<title>Uploader</title>
	<link href="/css/bootstrap.min.css" rel="stylesheet" media="screen">
</head>
<body>
    <?php
		if(!file_exists('../config.php')){
			die('Config file doesn\' exist. Did you forget to copy config.php.default to config.php?');
		}
		
		require_once('../config.php');
		require_once '../libs/sdk-1.5.17.1/sdk.class.php';
		
		$bucket = S3_BUCKET; //."_test";
		$allowedExts = array("jpg", "jpeg", "gif", "png");
		
		if (isset($_FILES["file"])) {
		
			$name = $_FILES["file"]["name"];
			$name_tokens = explode(".", $name);
			$extension = end($name_tokens);
			
			if ((($_FILES["file"]["type"] == "image/gif") || ($_FILES["file"]["type"] == "image/jpeg")
					|| ($_FILES["file"]["type"] == "image/png") || ($_FILES["file"]["type"] == "image/pjpeg"))
					&& in_array($extension, $allowedExts)) {
					
				if ($_FILES["file"]["error"] > 0) {
					echo "Return Code: " . $_FILES["file"]["error"] . "<br>";
				} else {
					echo "Upload: " . $_FILES["file"]["name"] . "<br>";
					//echo "Type: " . $_FILES["file"]["type"] . "<br>";
					echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
					//echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br>";
					
					// Instantiate the class
					$s3 = new AmazonS3();
					
					// Create object from a file
					$response = $s3->create_object(
						$bucket,
						$name,
						array(
							'fileUpload' => $_FILES['file']['tmp_name'],
							'acl' => AmazonS3::ACL_PUBLIC
							)
						);
					
					// Success?
					//if ($response->isOK()) {
						echo "<img src=\"http://".$bucket.".s3.amazonaws.com/".$name."\" alt=\"Image here...\" />";
						//echo "<img src=\"http://s3.amazonaws.com/".$bucket."/".$name."\" />";
					//}
					
				}
			} else {
				echo "Invalid file";
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