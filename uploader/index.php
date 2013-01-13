<?php
//if( !isset( $_COOKIE['dispatcher'] ) && $_COOKIE['dispatcher'] != 'boss' ){
    //die( 'oh no no.' );
//}
?>
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
		tr:nth-child(odd) {
			background-color: #EEE;
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
		
		if (isset($_POST["fk_text"])) {
			// notify via SMS
			$fk = $_POST["fk_text"];
			$text = "Get to the photo booth. It's your turn.";
			//echo $text." ".$fk;
			text_person($fk, $text);
		}
		
		if (isset($_POST["fk_del"])) {
			$fk = $_POST["fk_del"];
			$pk = $_POST["pk_del"];
			// close this user on queue
			$query = "UPDATE photoshop SET state = 0 WHERE users_fk = ".$fk." AND photoshop_pk = ".$pk;
			$DB->query($query);
		}
		
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
							'acl' => AmazonS3::ACL_PUBLIC,
							'contentType' => $_FILES["file"]["type"],
							'headers' => array(
								'Content-Disposition' => 'inline;filename='.$name,
							)
						));
						
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
						email_person($fk, "Photoshop", array("name" => $f, "url" => $link));
						
						// add points for this user
						$message = 'Vanity is the best sin.';
						$query = 'INSERT INTO points (accounts_fk, points, reason, created) VALUES ('.$fk.', '.PHOTOSHOP.', \''.$message.'\', CURRENT_TIMESTAMP)';
						$DB->query($query);
						
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
				echo '<tr><td><div style="height:50px;"><img src="'.$row['img'].'" width="50" height="50" /></div></td>';
				echo '<td>'.$disp_name.'</td>';
				if ($row['state'] == 1) {
					echo '<td width="40%"><form enctype="multipart/form-data" action="index.php" method="post">
							<input type="file" name="file" />
							<input type="hidden" name="fk" value="'.$row['fk'].'" />
							<input type="hidden" name="pk" value="'.$row['pk'].'" />
							<input type="hidden" name="f" value="'.$row['f'].'" />
							<input type="submit" value="Upload" /></form></td>';
					echo '<td width="20%"><form action="index.php" method="post">
							<input type="hidden" name="fk_text" value="'.$row['fk'].'" />
							<input type="submit" value="Call to booth via SMS" /></form>';
					echo '<td width="20%"><form action="index.php" method="post">
							<input type="hidden" name="fk_del" value="'.$row['fk'].'" />
							<input type="hidden" name="pk_del" value="'.$row['pk'].'" />
							<input type="submit" value="Remove from queue" /></form>';
				} else {
					echo '<td colspan="3">';
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
