<?php
session_start();
$root = $_SERVER['DOCUMENT_ROOT'];
include "$root/include/db-connect.php";

$id = $_POST['id'];
$title = $_POST['title'];
$content = $_POST['content'];

if (!empty($id) && is_numeric($id)) {
	$stmt = $con->prepare("update thoughts set thought_title = ?,
	thought_text = ? where thought_id = ?");
	$stmt->bind_param('ssi', $title, $content, $id);
} else {
	$stmt = $con->prepare("insert into thoughts(thought_title, thought_text)
		values(?, ?)");
	$stmt->bind_param('ss', $title, $content);
}
$result = $stmt->execute();

if ($result) {
	$_SESSION['msg'] = "Thought saved successfully";
} else {
	$_SESSION['msg'] = "Problem saving thought. {$stmt->error} :(";
}
header('Location: /admin/thoughts.php');
