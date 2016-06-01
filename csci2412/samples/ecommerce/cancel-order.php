<?php
	include('include/db-connect.php');
	include('include/OrderHandler.php');

	$id = $_GET['id'];

	// update the beer stock for each order item in the order
	$result = $con->query("SELECT beer_id, quantity from order_items WHERE order_id = $id");
	foreach ($result as $row) {
		OrderHandler::updateStock($row['beer_id'], $row['quantity'], Actions::Add, $con);
	}

	$result = $con->query("DELETE FROM orders WHERE order_id = $id");
	if ($result)
		header("Location: orders.php?d=1");
	else
		echo '<div class="error">' . $con->error . '</div>';
?>