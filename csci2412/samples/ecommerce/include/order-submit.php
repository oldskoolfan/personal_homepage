<?php // this is our "main" postback-handler function

	// check form for errors...if found, display errors and exit
	$errors = OrderHandler::validateForm($_POST); // static method call
	if (count($errors) > 0) { // houston, we have a problem...
		echo '<div class="error">The following error(s) occurred...';
		echo '<ul>';
		foreach ($errors as $err) {
			echo "<li>$err</li>";
		}
		echo '</ul></div>';
		return;
	}

	// initialize some local variables from POST values
	$firstName = $_POST['firstname'];
	$lastName = $_POST['lastname'];
	$address = $_POST['address'];
	$city = $_POST['city'];
	$state = $_POST['state'];
	$zip = $_POST['zipcode'];
	$orderId = isset($_POST['orderid']) ? $_POST['orderid'] : false;

	// get other info we need...i.e. what beers are being ordered
	$beerQuantities = [];
	foreach ($_POST as $key => $value) {
		if (strpos($key, '-')) { // must be a quantity key (e.g. quantity-1)
			$quanId = explode('-', $key)[1];
			$beerQuantities[$quanId] = $value;
		}
	}
	// make sure the quantities being ordered are available
	foreach ($beerQuantities as $id => $quan) {
		$beer = OrderHandler::getBeerById($productManager->currentProducts, $id);
		if ($beer->id == $id && !$beer->isQuantityInStock($quan))
			return;
	}

	// all clear...show order confirmation, save to db
	list($tax, $subtotal, $total) = 
		OrderHandler::calculateTotals($beerQuantities, $productManager->currentProducts);

	$result = OrderHandler::updateContactInfo($firstName, $lastName, $address, $city, $state,
		$zip, $con);
	if (!$result) return;

	/* Now we're ready to start building our confirmation text, which we will concatenate
	 * to a variable and spit out at the bottom of the index page (inside div.confirm)
	 */

	$responseText = '';

	if (!$orderId) {
		$responseText .= '<h3>Thank you for your order!</h2><h3>Order Confirmation:</h3>';
		// create a new order 
		$orderId = OrderHandler::createOrder($subtotal, $tax, $total, $con);
		if (!$orderId) return;

		// save items
		foreach($beerQuantities as $id => $quan)
			if (!OrderHandler::createOrderItem($id, $orderId, $quan, $con))
				return;
	}
	else { // update existing order and order items
		$responseText .= 
			'<h3>Your order was updated successfully!</h2><h3>Order Confirmation:</h3>';
		if (!OrderHandler::updateOrder($orderId, $subtotal, $tax, $total, $con)) return;
		foreach($beerQuantities as $id => $quan) {
			$item = OrderHandler::getBeerById($_SESSION['editItems'], $id);
			if ($item) {
				if (!OrderHandler::updateOrderItem($id, $orderId, $quan, $con))
					return;
			}
			else {
				if (!OrderHandler::createOrderItem($id, $orderId, $quan, $con))
					return;
			}			
		}
	}
	// we could have an order with no order items at this point, giving us a total of $0.00
	// if this is the case, we need to delete the order, so reroute to delete page
	if ($total == 0) header('Location: cancel-order.php?id=' . $orderId);

	$responseText .= "<table>
		<tr><td>First Name:</td><td>$firstName</td></tr>
		<tr><td>Last Name:</td><td>$lastName</td></tr>
		<tr><td>Address:</td><td>$address $city, $state $zip</td></tr>";
	
	foreach ($beerQuantities as $id => $quan) {
		if (!empty($quan) && $quan > 0) {
			$beer = OrderHandler::getBeerById($productManager->currentProducts, $id);
			$responseText .= "<tr><td>$beer->name</td><td>$quan @ $$beer->price</td></tr>";
		}
	}

	$responseText .= "<tr><td>Subtotal:</td><td>$$subtotal</td></tr>
		<tr><td>Tax:</td><td>$$tax</td></tr>
		<tr><td>Total:</td><td>$$total</td></tr>
		</table>";

?>