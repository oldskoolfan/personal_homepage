<?php
	include "include/header.php";
	include "include/db-connect.php";

	echo "<h2>Your Profile</h2>";

	$user = $con->query("SELECT * FROM users WHERE user_id = {$_SESSION['id']}")->fetch_assoc();
	$date = new DateTime($user['join_date']);
?>
	<div class="greeting">
		<a href="./">Home</a>
		<a href="logout.php">Log Out</a>
	</div>
	<table id="ProfileTable">
	<tr><td>First Name</td><td><?=$user['firstname']?></td></tr>
	<tr><td>Last Name</td><td><?=$user['lastname']?></td></tr>
	<tr><td>Joined</td><td><?=$date->format('m-d-Y')?></td></tr>
	<tr><td>Address</td><td><?=$user['address']?></td></tr>
	<tr><td>City</td><td><?=$user['city']?></td></tr>
	<tr><td>State</td><td><?=$user['state']?></td></tr>
	<tr><td>Zipcode</td><td><?=$user['zipcode']?></td></tr>
	</table>

<?php include "include/footer.html" ?>
