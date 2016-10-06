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
	$filename = $_FILES['song']['name'];
	$tmpPath = $_FILES['song']['tmp_name'];
	$destPath = "$root/assets/songs/$filename";
	$type = $_FILES['song']['type'];
	var_dump($_FILES['song']);
	if (file_exists($tmpPath) && move_uploaded_file($tmpPath, $destPath)) {
		$stmt = $con->prepare('insert into songs(song_title,
			album_id, recorded_date, filename, file_type,
			credits) values(?,?,?,?,?,?)');
		$stmt->bind_param('ssssss', $title, $albumId, $date,
			$filename, $type, $credits);
		$result = $stmt->execute();
	}
}

if ($result) {
	$_SESSION['msg'] = 'Song saved successfully';
} else {
	$_SESSION['msg'] = 'problem saving song' . $stmt->error;
}
header('Location: /admin/music.php');
