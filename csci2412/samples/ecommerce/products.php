<?php
	include 'include/header.php';
	if (!isset($_SESSION['id'])) header('Location: login.php');
	if (!$_SESSION['isAdmin']) header('Location: index.php');

	include 'include/db-connect.php'; // gives us $con db connection variable
	include 'include/ProductManager.php'; // gives us $productManager object variable

	echo '<br><br><h2>Current Products</h2><br>';

?>
<div class="greeting">
	<span>Hi, <?=$_SESSION['username']?>!</span>
	<a href="./">Home</a>
	<a href="logout.php">Log Out</a>
</div>
<?php

	// start our existing products table
	echo '<table id="ProductTable">
			<tr>
				<th>Name</th><th>Family</th><th>Price</th><th>Quantity</th><th>Active</th><th>Action</th>
			</tr>';

	// build table rows with the beers we have to offer
	foreach ($productManager->currentProducts as $beer) {
		$active = $beer->isActive ? "yes" : "no";
		$activeLink = $beer->isActive ? "Deactivate" : "Activate";
		$activeParam = $beer->isActive ? "false" : "true";
		echo "<tr><td>$beer->name</td><td>$beer->family</td><td>$beer->price</td><td>$beer->inStock</td>
			<td>$active</td><td><a href=\"edit-product.php?id=$beer->id\">Edit</a>
			<a href=\"set-product-status.php?id=$beer->id&setStatus=$activeParam\">$activeLink</a></td></tr>";
	}
	echo '</table>';
?>
<br>
<button onclick="location='edit-product.php'" style="font:inherit">Add New Product</button>
<?php include "include/footer.html" ?>