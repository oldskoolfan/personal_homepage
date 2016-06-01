<?php
	include 'include/header.php';
	if (!isset($_SESSION['id'])) header('Location: login.php');
	if (!$_SESSION['isAdmin']) header('Location: index.php');

	include 'include/db-connect.php'; // gives us $con db connection variable

	echo '<br><br><h2>All Orders</h2><br>';

?>
<div class="greeting">
	<span>Hi, <?=$_SESSION['username']?>!</span>
	<a href="./">Home</a>
	<a href="logout.php">Log Out</a>
</div>
<table id="OrderTable">
<tr><th>Order Number</th><th>Order Date</th><th>Customer Name</th><th>Total</th>
	<th>Order Status</th><th>Action</th></tr>
<?php
	$result = $con->query("SELECT * FROM orders o JOIN users u ON o.user_id = u.user_id JOIN
		order_status_ref r on o.order_status_id = r.order_status_id WHERE o.order_status_id = 1 
		ORDER BY o.last_mod_date DESC, o.user_id");
	foreach ($result as $row) {
		echo '<tr><td>' . $row['order_id'] . '</td>' .
			'<td>' . $row['order_date'] . '</td>' .
			'<td>' . $row['firstname'] . ' ' . $row['lastname'] . '</td>' .
			'<td>' . $row['order_total'] . '</td>' .
			'<td>' . $row['order_status'] . '</td>' .
			'<td><a href="process-order.php?id=' . $row['order_id'] . '"">Process</a></td></tr>';
	}
?>
</table>
<?php include "include/footer.html" ?>