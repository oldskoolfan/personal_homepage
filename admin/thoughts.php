<?php
	session_start();
	$root = $_SERVER['DOCUMENT_ROOT'];
	include "$root/include/header.php";
	include "$root/include/db-connect.php";
	$thoughts = $con->query('select * from thoughts');
?>
<h1>Thought Admin</h1>
<a href="/admin">Go Back</a>
<section id="music_section" class="form-wrapper">
	<?php
	if (isset($_SESSION['msg'])) {
		$msg = $_SESSION['msg'];
		echo "<script>alert('$msg')</script>";
		unset($_SESSION['msg']);
	}
	?>

	<!-- forms -->
	<button id="thought_form_btn">Record a thought</button>
	<div id="thought_form">
	<?php include 'include/thought-form.php' ?>
	</div>
	<br>
	<!-- albums -->
	<h3>My Thoughts</h3>
	<div class="thoughts">
	<?php if($thoughts): ?>
		<?php while($thought = $thoughts->fetch_object()): ?>
			<div class="thought-link">
				<a href="thought.php?id=<?=$thought->thought_id?>"><?=$thought->thought_title?></a> |
				<a href="delete.php?entity=thought&id=<?=$thought->thought_id?>" onclick="return confirm('Are you sure?');">Delete</a>
			</div>
		<?php endwhile; ?>
	<?php endif; ?>
	</div>
</section>
<script src="assets/js/main.js"></script>
<?php include "{$_SERVER['DOCUMENT_ROOT']}/include/footer.php" ?>
