<?php
include "include/Beer.php";
class ProductManager {
	private $con;
	public $currentProducts = []; // this will be a collection of beer objects
	public $beerFamilies = [];

	function __construct($dbConnection) {
		$this->con = $dbConnection;

		// get current products
		$stmt = $this->con->prepare("SELECT * FROM beers b join beer_families f on b.family_id =
			f.family_id");
		if ($stmt->execute()) {
			$result = $stmt->get_result();
			foreach ($result as $row) {
				array_push(
					$this->currentProducts, 
					new Beer(
						$row['beer_id'],
						$row['beer_name'],
						$row['family_name'],
						$row['cost'],
						$row['stock'],
						$row['is_active']
					)
				);
			}
		}
		else { die("ERROR: $con->error"); }

		// get beer families
		$stmt = $this->con->prepare("SELECT * FROM beer_families ORDER BY family_name");
		if ($stmt->execute()) {
			$result = $stmt->get_result();
			foreach($result as $row) {
				array_push(
					$this->beerFamilies,
					new Family(
						$row['family_id'],
						$row['family_name']
					)
				);
			}
		}
		else { die("ERROR: $con->error"); }		
	}

	public function saveBeerInfo() {
		try {
			if (empty($_POST['name']) || empty($_POST['family']) || empty($_POST['price']) ||
				empty($_POST['stock']))
				throw new Exception("All fields are required.");
			$name = $_POST['name'];
			$family = $_POST['family'];
			$price = $_POST['price'];
			$stock = $_POST['stock'];
			if (isset($_GET['id'])) {
				$id = $_GET['id'];
				$stmt = $this->con->prepare("UPDATE beers SET family_id = ?, beer_name = ?,
					cost = ?, stock = ? WHERE beer_id = ?");
				$stmt->bind_param("isdii", $family, $name, $price, $stock, $id);
				if (!$stmt->execute())
					throw new Exception($con->error);
			}
			else {
				$stmt = $this->con->prepare("INSERT INTO beers (family_id, beer_name, cost,
					stock, created_date) VALUES (?, ?, ?, ?, now())");
				$stmt->bind_param("isdi", $family, $name, $price, $stock);
				if (!$stmt->execute())
					throw new Exception($con->error);
			}
			header("Location: products.php");
		}
		catch (Exception $ex) {
			echo '<div class="error">Error: ' . $ex->getMessage() . '</div>';
		}
	}
}

$productManager = new ProductManager($con);