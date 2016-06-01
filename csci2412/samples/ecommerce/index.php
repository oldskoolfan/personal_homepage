<?php
	// our html header and class files
	include('include/header.php');
	include('include/db-connect.php');
	include('include/OrderHandler.php');
	include('include/ProductManager.php');

	if (!isset($_SESSION['id']))
		header('Location: login.php');
?>
		<h6>Note: All beers come in 22oz bottles</h6>
		<?= $_SESSION['edit'] ? 
			'<br><h2>***Please Edit Your Order and Click Save***</h2>' : '' ?>
		<div class="greeting">
			<span>Hi, <?=$_SESSION['username']?>!</span>
			<?= $_SESSION['edit'] ? '<a href="./">Home</a>' : '' ?>
			<a href="orders.php">View My Orders</a>
			<a href="profile.php">My Profile</a>
			<?= $_SESSION['isAdmin'] ? 
				'<a href="process-orders.php">Process Orders</a><a href="products.php">Edit Products</a>' : '' ?>
			<a href="logout.php">Log Out</a>
		</div>
		<?php if ($_SERVER['REQUEST_METHOD'] == 'POST') include('include/order-submit.php');?>
		<form action="" method="post">
			<fieldset>
				<legend>Order Calculator</legend>
				<label>First Name:
					<input name="firstname" type="text" maxlength="50"
						value="<?php if ($_SESSION['edit'] && isset($_POST['firstname'])) echo $_POST['firstname']?>"
					>
				</label>
				<label>Last Name:
					<input name="lastname" type="text" maxlength="50"
						value="<?php if ($_SESSION['edit'] && isset($_POST['lastname'])) echo $_POST['lastname']?>"
					>
				</label>
				<label>Street Address:
					<input name="address" type="text" maxlength="50"
						value="<?php if ($_SESSION['edit'] && isset($_POST['address'])) echo $_POST['address']?>"
					>
				</label>
				<label>City:
					<input name="city" type="text" maxlength="50"
						value="<?php if ($_SESSION['edit'] && isset($_POST['city'])) echo $_POST['city']?>"
					>
				</label>
				<label>State:
					<input name="state" type="text" maxlength="2"
						value="<?php if ($_SESSION['edit'] && isset($_POST['state'])) echo $_POST['state']?>"
					>
				</label>
				<label>Zipcode:
					<input name="zipcode" type="text" maxlength="5"
						value="<?php if ($_SESSION['edit'] && isset($_POST['zipcode'])) echo $_POST['zipcode']?>"
					>
				</label>
				<input type="hidden" name="orderid"
					value="<?php if ($_SESSION['edit'] && isset($_POST['orderid'])) echo $_POST['orderid']?>">

				<!-- product section -->
				<label>Beer:
					<table>
					<tr>
						<th>Name</th><th>Family</th><th>Price</th><th>Avail</th><th>Quantity</th>
					</tr>
					
				<?php 
					// we re-instantiate this object here so we have updated quantities in stock
					// (in case we are doing a postback for an order)
					$productManager = new ProductManager($con);

					// loop through beer array to create our beer row options
					foreach ($productManager->currentProducts as $beer) {
						if ($beer->isActive) {
							if ($_SESSION['edit'] && isset($_SESSION['editItems'])) {
								$item = OrderHandler::getBeerById($_SESSION['editItems'], 
									$beer->id);
								$val = $item ? "value=\"{$item['quantity']}\"" : '';
							}
							else {
								$val = '';
							}
							echo "<tr><td>$beer->name</td><td>$beer->family</td>
								<td>$beer->price</td><td>$beer->inStock</td><td>
								<input name=\"quantity-{$beer->id}\" type=\"text\" $val>
								</td></tr>";
						}
					}
				?>
					</table>
				</label>
				<!-- end product section -->
				<input type="submit" value="Save">
			</fieldset>
		</form>
		<!-- END form -->
		<?php $_SESSION['edit'] = false ?>
		<!-- BEGIN order confirmation section -->
		<div class="confirm">
		<?php if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($responseText)) echo $responseText; ?>
		</div>
<?php include('include/footer.html');
