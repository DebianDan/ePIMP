<!DOCTYPE html>
<html>
<head>
	<title>Uploader</title>
	<!--<link href="/css/bootstrap.min.css" rel="stylesheet" media="screen">-->
	<style>
		td input {
			line-height: normal !important;
			height: auto !important;
		}
		form { margin: 0; }
		table {
			width: 100%;
		}
		td {
			padding: 10px;
			vertical-align: middle;
		}
	</style>
</head>
<body>
    <?php
		if(!file_exists('../config.php')){
			die('Config file doesn\' exist. Did you forget to copy config.php.default to config.php?');
		}
		
		require_once('../config.php');
		require_once '../libs/sdk-1.5.17.1/sdk.class.php';
		
		$DB = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
		if (mysqli_connect_errno()) {
			printf("Connect failed: %s\n", mysqli_connect_error());
			exit();
		}
		
		$bucket = S3_BUCKET; //."_test";
		$allowedExts = array("jpg", "jpeg", "gif", "png");
		
		if (isset($_FILES["file"]) && isset($_POST["fk"]) && isset($_POST["pk"])) {

			$name = $_FILES["file"]["name"];
			$name_tokens = explode(".", $name);
			$extension = end($name_tokens);

			if ((($_FILES["file"]["type"] == "image/gif") || ($_FILES["file"]["type"] == "image/jpeg")
					|| ($_FILES["file"]["type"] == "image/png") || ($_FILES["file"]["type"] == "image/pjpeg"))
					&& in_array($extension, $allowedExts)) {

				if ($_FILES["file"]["error"] > 0) {
					echo "Return Code: " . $_FILES["file"]["error"] . "<br>";
				} else {
					//echo "Upload: " . $_FILES["file"]["name"] . "<br>";
					//echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
					//echo "Type: " . $_FILES["file"]["type"] . "<br>";
					//echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br>";

					$s3 = new AmazonS3();
					$response = $s3->create_object(
						$bucket,
						$name,
						array(
							'fileUpload' => $_FILES['file']['tmp_name'],
							'acl' => AmazonS3::ACL_PUBLIC
							)
						);
						
					if ($response->isOK()) {
						$link = "http://".$bucket.".s3.amazonaws.com/".$name;
						//echo "<img src=\"".$link."\" alt=\"Image here...\" /><br/>";
						$fk = $_POST["fk"];
						$pk = $_POST["pk"];
						$f = $_POST["f"];
						//echo $link."<br>";
						
						// close this user on queue, update picture
						$query = "UPDATE photoshop SET state = 0, image_url='".$link."' WHERE users_fk = ".$fk." AND photoshop_pk = ".$pk;
						$DB->query($query);
						
						// notify this user by email
						email_person($fk, "Photoshop", array(
							"name" => $f,
							"url" => $link
						));
						
						printQueue($DB);
					}

				}
			} else {
				echo "Invalid file";
			}
		} else {
			printQueue($DB);
		}
		
		mysqli_close($DB);
		
		function printQueue($DB){
			// load queue
			$query = 'SELECT a.first_name f, a.last_name l, ps.users_fk fk, ps.photoshop_pk pk, ps.image_url img, ps.state state FROM photoshop ps JOIN accounts a ON ps.users_fk = a.accounts_pk';
			$result = $DB->query($query) or die($DB->error.__LINE__);
			
			// print queue
			echo '<table>';
			while($row = $result->fetch_assoc()) {
				$disp_name = $row['f'].' '.$row['l'];
				echo '<tr><td><img src="'.$row['img'].'" width="50" height="50" /></td>';
				echo '<td>'.$disp_name.'</td><td>';
				if ($row['state'] == 1) {
					echo '<form enctype="multipart/form-data" action="index.php" method="post">
							<input type="file" name="file" />
							<input type="hidden" name="fk" value="'.$row['fk'].'" />
							<input type="hidden" name="pk" value="'.$row['pk'].'" />
							<input type="hidden" name="f" value="'.$row['f'].'" />
							<input type="submit" value="Upload" /></form>';
				}
				echo '</td></tr>';
			}
			echo '</table>';
		}
	?>
	<script src="http://code.jquery.com/jquery-latest.js"></script>
	<!--<script src="/js/bootstrap.min.js"></script>-->
</body>
</html>