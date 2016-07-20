<?php
	$start = 0;
	// do we need a specific range?
	if (isset($_SERVER['HTTP_RANGE'])) {
		$range = $_SERVER['HTTP_RANGE'];
		$start = preg_replace('/[^0-9]/', '', $range);
	}

	include "db-connect.php";
	$id = $_GET['id'];
	$result = $con->query('select audio, file_type from songs
		where song_id = ' . $id);
	if ($result) {
		$song = $result->fetch_object();
		$type = $song->file_type;
		$data = $song->audio;
		$size = strlen($data);
		$lastByte = $size - 1;
		http_response_code(206);
		header('Content-Type: audio/mpeg');
		header('Content-Length: ' . $size);
		header("Content-Range: bytes $start-$lastByte/$size");
		echo substr($data, $start);
	}
?>
