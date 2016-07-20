<?php

session_start();
$root = $_SERVER['DOCUMENT_ROOT'];
include "$root/include/db-connect.php";

$title = $_POST['title'];
$albumId = $_POST['album'];
$credits = $_POST['credits'];
$date = $_POST['date'];
$result = false;

if (isset($_FILES['song'])) {
	$path = $_FILES['song']['tmp_name'];
	$type = $_FILES['song']['type'];
	if (file_exists($path)) {
		$stream = fopen($path, 'r');
		$audio = fread($stream, filesize($path));
		fclose($stream);
		$stmt = $con->prepare('insert into songs(song_title,
			album_id, recorded_date, audio, file_type,
			credits) values(?,?,?,?,?,?)');
		$stmt->bind_param('ssssss', $title, $albumId, $date,
			$audio, $type, $credits);
		$result = $stmt->execute();
	}
}

if ($result) {
	$_SESSION['msg'] = 'Song saved successfully';
} else {
	$_SESSION['msg'] = 'problem saving song';
}
header('Location: /admin/music.php');
