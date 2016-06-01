<?php

abstract class Actions {
	const Subtract = 0;
	const Add = 1;
}

class OrderHandler {
	public static $salesTax = 0.0675; // make static property

	// static methods
	public static function validateForm($post) {
		$errors = [];

		// all fields are required
		if (empty($post['firstname']) || 
			empty($post['lastname']) ||
			empty($post['address']) ||
			empty($post['city']) ||
			empty($post['state']) ||
			empty($post['zipcode'])) {
			array_push($errors, 'Please enter all required fields.');
		}
		if (!is_numeric($post['zipcode'])) {
			array_push($errors, 'Please enter a valid zipcode.');
		}

		return $errors;
	}

	public static function calculateTotals($beerOrders, $beers) {
		$totals = [];
		$subTotal = 0;
		$tax = 0;
		$total = 0;
		foreach ($beerOrders as $id => $quan) {
			$beer = OrderHandler::getBeerById($beers, $id);
			$subTotal += $beer->price * $quan;
		}
		$tax = $subTotal * OrderHandler::$salesTax;
		$total = $subTotal + $tax;
		array_push(
			$totals, 
			number_format($tax, 2), 
			number_format($subTotal, 2), 
			number_format($total, 2));
		return $totals;
	}

	public static function getBeerById($array, $id) {
		foreach ($array as $beer)
			if (is_object($beer) && $beer->id == $id) 
				return $beer;
			elseif (is_array($beer) && $beer['id'] == $id)
				return $beer;
		return false;
	}

	public static function createOrder($sub, $tax, $total, $db) {
		$result = $db->query("INSERT INTO orders (user_id, order_date, order_subtotal,
			order_tax, order_total) VALUES ({$_SESSION['id']}, now(), $sub, $tax, $total)");
		if ($result) {
			$result = $db->query("SELECT order_id FROM orders ORDER BY last_mod_date
				DESC LIMIT 1");
			return $result->fetch_row()[0];
		}
		else {
			OrderHandler::displayError($db->error);
			return false;
		}
	}

	public static function updateOrder($id, $sub, $tax, $total, $db) {
		$result = $db->query("UPDATE orders SET order_subtotal = $sub, order_tax = $tax,
			order_total = $total WHERE order_id = $id");
		if (!$result) {
			OrderHandler::displayError($db->error);
			return false;
		}
		return true;
	}

	public static function updateStock($id, $quan, $action, $db) {
		$q = "SELECT stock from beers WHERE beer_id = $id";
		$stock = $db->query($q)->fetch_assoc()['stock']; // should be single row
		//$diff = $stock - $quan;
		$newQuan = 0;
		if ($action == Actions::Subtract) // we need to subtract
			$newQuan = $stock - $quan;
		elseif ($action == Actions::Add) // we need to add
			$newQuan = $stock + $quan;
		// update with new quantity
		$result = $db->query("UPDATE beers SET stock = $newQuan WHERE beer_id = $id");
		if (!$result) {
			OrderHandler::displayError($db->error);
			return false;
		}
		return true;
	}

	public static function createOrderItem($beerId, $orderId, $quan, $db) {
		if (empty($quan) || $quan == 0) return true;
		if (!OrderHandler::updateStock($beerId, $quan, Actions::Subtract, $db)) 
			return false;
		$result = $db->query("INSERT INTO order_items(beer_id, order_id, quantity, created_date)
			VALUES ($beerId, $orderId, $quan, now())");
		if (!$result) {
			OrderHandler::displayError($db->error);
			return false;
		}
		return true;
	}

	public static function updateOrderItem($beerId, $orderId, $quan, $db) {

		// update the amount of beer in stock based on the previous order quantity
		$q = "SELECT quantity FROM order_items WHERE order_id = $orderId and beer_id = $beerId";
		$prevOrderQuan = $db->query($q)->fetch_assoc()['quantity'];
		$diff = $prevOrderQuan - $quan; // sign of $diff tells us what to do
		$action = $diff < 0 ? Actions::Subtract : Actions::Add;
		if (!OrderHandler::updateStock($beerId, abs($diff), $action, $db))
			return false;

		// if quan is 0 or empty, we should delete the order item
		if (empty($quan) || $quan == 0)
			$result = $db->query("DELETE FROM order_items WHERE beer_id = $beerId");
		else
			// update the order item
			$result = $db->query("UPDATE order_items SET quantity = $quan 
				WHERE order_id = $orderId and beer_id = $beerId");
		if (!$result) {
			OrderHandler::displayError($db->error);
			return false;
		}
		return true;
	}	

	public static function updateContactInfo($fname, $lname, $address, $city, $state, $zip, 
		$db) {
		return $db->query("UPDATE users SET firstname = '$fname', lastname = '$lname',
			address = '$address', city = '$city', state = '$state', zipcode = '$zip' 
			WHERE user_id = '{$_SESSION['id']}'");
	}

	static function displayError($err) {
		echo '<div class="error">Error: ' . $err . '</div>';
	}
}
?>