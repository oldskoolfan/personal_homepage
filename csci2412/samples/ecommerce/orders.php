<?php 
	include('include/header.php');
	if (!isset($_SESSION['id']))
		header('Location: login.php');
?>
<h2>Your Orders</h2>
<?=isset($_GET['d']) && $_GET['d'] == 1 ? 
	'<h3 style="padding-top:30px">***Order deleted successfully!***</h3>' : '' ?>
<div class="greeting">
	<span>Hi, <?=$_SESSION['username']?>!</span>
	<a href="./">Home</a>
	<a href="logout.php">Log Out</a>
</div>
<table class="orders">
<tr><th>Order Number</th><th>Order Date</th><th>Number of Items</th><th>Subtotal</th>
	<th>Tax</th><th>Total</th><th>Status</th><th>Action</th></tr>
	<?php
		include('include/db-connect.php');
		$result = $con->query("SELECT o.*, r.order_status, sum(i.quantity) as items 
			FROM orders o JOIN order_items i
			ON o.order_id = i.order_id JOIN order_status_ref r
			ON o.order_status_id = r.order_status_id
			WHERE user_id = {$_SESSION['id']}
			GROUP BY o.order_id");
		if ($result) {
			if ($result->num_rows > 0) {
				foreach ($result as $order) {
					$date = new DateTime($order['order_date']);
					$actions = $order['order_status'] == 'queued' ? 
						'<a href="edit-order.php?id=' . $order['order_id'] .
						'">Edit</a>&nbsp;<a href="cancel-order.php?id=' . $order['order_id'] .
						'">Delete</a>' : '';  
					echo '<tr><td>' . $order['order_id'] . '</td>' .
						'<td>' . $date->format('m/d/Y') . '</td>' .
						'<td>' . $order['items'] . '</td>' .
						'<td>' . $order['order_subtotal'] . '</td>' .
						'<td>' . $order['order_tax'] . '</td>' .
						'<td>' . $order['order_total'] . '</td>' .
						'<td>' . $order['order_status'] . '</td>' . 
						'<td>' . $actions . '</td></tr>';
				}
			}
			else
				echo '<tr><td style="padding:30px">No orders to display.</td><tr>';
		}
		else {
			echo 'Error: ' . $con->error;
		}
	?>
</table>
<?php include('include/footer.html');?>