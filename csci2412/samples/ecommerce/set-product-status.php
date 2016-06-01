<?php
	session_start();
	if (!isset($_SESSION['isAdmin']) || !$_SESSION['isAdmin']) {
		echo '<div class="error">Not Authorized.</div>';
		return;
	}
	include 'include/db-connect.php';
	$id = $_GET['id'];
	$status = $_GET['setStatus'] == 'true' ? 1 : 0;

	// we don't actually want to delete the record from the database, just set the active flag to false
	$stmt = $con->prepare("UPDATE beers SET is_active = ? WHERE beer_id = ?");
	$stmt->bind_param("ii", $status, $id);
	$result = $stmt->execute();
	
	if ($result)
		header('Location: products.php?d=1');
	else
		echo '<div class="error">' . $con->error . '</div>';
?>