<?php
	include 'include/header.php';
	if (!isset($_SESSION['id'])) header('Location: login.php');
	if (!$_SESSION['isAdmin']) header('Location: index.php');

	include 'include/db-connect.php'; // gives us $con db connection variable
	include 'include/ProductManager.php'; // gives us $productManager object variable
	include 'include/OrderHandler.php';

	if (isset($_GET['id']) && $_SERVER['REQUEST_METHOD'] != 'POST') {
		$beer = OrderHandler::getBeerById($productManager->currentProducts, $_GET['id']);
		$_POST['name'] = $beer->name;
		foreach ($productManager->beerFamilies as $family) {
			if ($family->name == $beer->family) {
				$_POST['family'] = $family->id;
				break;
			}
		}
		$_POST['price'] = $beer->price;
		$_POST['stock'] = $beer->inStock;
	}

	echo '<br><br><h2>Add/Edit Product Information</h2><br>';
?>
<br>
<?php if ($_SERVER['REQUEST_METHOD'] == 'POST') $productManager->saveBeerInfo() ?>
<div class="edit-product">
<form action="" method="post">
<fieldset><legend>Beer Info</legend>
	<label>Beer Name:
		<input type="text" name="name" value="<?=isset($_POST['name']) ? $_POST['name'] : ''?>">
	</label>
	<label>Family:
		<select name="family">
		<option></option>
		<?php foreach ($productManager->beerFamilies as $family): ?>
			<option value="<?=$family->id?>" 
				<?=isset($_POST['family']) && $family->id == $_POST['family'] ? ' selected' : ''?>><?=$family->name?></option>
		<?php endforeach; ?>
		</select>
	</label>
	<label>Unit Price:
		<input type="text" name="price" value="<?=isset($_POST['price']) ? $_POST['price'] : ''?>">
	</label>
	<label>Current Stock:
		<input type="text" name="stock" value="<?=isset($_POST['stock']) ? $_POST['stock'] : ''?>">
	</label>
	<input type="submit" value="Save Beer Info">
</fieldset>
</form>
<a href="products.php">Back to Product List</a>
</div>
<?php include "include/footer.html" ?>