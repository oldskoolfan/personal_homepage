<?php
session_start();
$root = $_SERVER['DOCUMENT_ROOT'];
include "$root/include/db-connect.php";

$id = $_POST['id'];
$title = $_POST['title'];
$desc = $_POST['description'];
$year = $_POST['year'];
$credits = $_POST['credits'];

if (!empty($id) && is_numeric($id)) {
	$stmt = $con->prepare("update albums set album_title = ?, album_desc = ?,
		credits = ?, release_year = ? where album_id = ?");
	$stmt->bind_param('ssssi', $title, $desc, $credits, $year, $id);
} else {
	$stmt = $con->prepare("insert into albums(album_title, album_desc,
		credits, release_year) values(?, ?, ?, ?)");
	$stmt->bind_param('ssss', $title, $desc, $credits, $year);
}
$result = $stmt->execute();

if ($result) {
	$_SESSION['msg'] = "Album saved successfully";
} else {
	$_SESSION['msg'] = "Problem saving album.";
}
header('Location: /admin/music.php');
