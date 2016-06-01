<?php
	session_start();
	if (!isset($_SESSION['isAdmin']) || !$_SESSION['isAdmin']) {
		echo '<div class="error">Not Authorized.</div>';
		return;
	}
	include 'include/db-connect.php';
	$id = $_GET['id'];

	$stmt = $con->prepare("UPDATE orders SET order_status_id = 2 WHERE order_id = ?");
	$stmt->bind_param("i", $id);
	$result = $stmt->execute();

	if ($result)
		header('Location: process-orders.php');
	else
		echo '<div class="error">' . $con->error . '</div>';
?>