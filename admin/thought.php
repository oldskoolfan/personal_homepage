<?php
	session_start();
	$root = $_SERVER['DOCUMENT_ROOT'];
	include "$root/include/header.php";
	include "$root/include/db-connect.php";
	$stmt = $con->prepare('select * from thoughts where thought_id = ?');
	$stmt->bind_param('i', $id);

	// check for id, redirect if not found
	if (!isset($_GET['id'])) {
		header('Location: thoughts.php');
	}
	$id = $_GET['id'];
	$success = $stmt->execute();
	if ($success) {
		$result = $stmt->get_result();
		$thought = $result->fetch_object();
		$title = $thought->thought_title;
		$content = $thought->thought_text;
	} else {
		echo '<div class="error">Error fetching thought</div>';
	}
?>
<?php if ($success):?>
<a href="thoughts.php">Go Back</a>
<section class="form-wrapper">
<?php include "include/thought-form.php" ?>
</section>
<script src="assets/js/main.js"></script>
<?php endif;?>
