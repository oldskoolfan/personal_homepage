<?php
	include "db-connect.php";
	$id = $_GET['id'];
	$result = $con->query('select audio, file_type from songs
		where song_id = ' . $id);
	if ($result) {
		$song = $result->fetch_object();
		$type = $song->file_type;
		$data = $song->audio;
		header('Content-Type: audio/mpeg');
		echo $data;
	}
?>
