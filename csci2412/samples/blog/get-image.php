<?php
	/*
		This is our "proxy" script for getting image data (bytes) and converting to an image that can
		be viewed on the web
	*/
	include "mysqli_connect.php";
	
	if (isset($_GET['image'])) { // check for fileID parameter
		$id = $_GET['image'];

		$connection = Blog::getDbConnection(); // connect to db
		$stmt = $connection->prepare("SELECT FileName, ImageData FROM images WHERE ImageId = ?");
		$stmt->bind_param('s', $id);
		$stmt->execute();
		$result = $stmt->get_result(); // run query
		if ($result) {
			$image = $result->fetch_assoc(); // should only be one result
			$nameArray = explode('.', $image['FileName']); // break filename into array[ filename, ext ]
			$ext = $nameArray[count($nameArray) - 1]; // get the extension from the array
			
			header('Content-Type: image/' . $ext); // send image to browser
			echo $image['ImageData'];
			
			/* another way to get images on the screen, but not necessary...
			$img = imagecreatefromstring($image['ImageData']); // creates an image resource for the browser
			if ( strpos(strtolower($ext), 'jpg') !== false || strpos(strtolower($ext), 'jpeg') !== false ) {
				imagejpeg($img);
			}
			elseif ( strpos(strtolower($ext), 'png') !== false ) {
				imagepng($img);
			}
			elseif ( strpos(strtolower($ext), 'gif') !== false ) {
				imagegif($img);
			}
			imagedestroy($img);
			*/
		}
	}

?>
