<?php
	session_start();
	$root = $_SERVER['DOCUMENT_ROOT'];
	include "$root/include/header.php";
	include "$root/include/db-connect.php";
	$albums = $con->query('select * from albums');
?>
<h1>Music Admin</h1>
<section id="music_section" class="form-wrapper">

<?php
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		include "include/music-form-handler.php";
	}
	if (isset($_SESSION['msg'])) {
		$msg = $_SESSION['msg'];
		echo "<script>alert('$msg')</script>";
		unset($_SESSION['msg']);
	}
?>

<!-- forms -->
<button id="album_form_btn">Create a new album</button>
<div id="album_form">
<?php include 'include/album-form.php' ?>
</div>
<button id="song_form_btn">Upload a song</button>
<div id="song_form">
<?php include 'include/song-form.php' ?>
</div>
<br>
<!-- albums -->
<h3>My Albums</h3>
<div class="albums">
<?php if($albums): ?>
	<?php while($album = $albums->fetch_object()): ?>
		<div class="album-link">
			<a href="album.php?id=<?=$album->album_id?>"><?=$album->album_title?></a>
		</div>
	<?php endwhile; ?>
<?php endif; ?>
</div>
</section>
<script src="assets/js/main.js"></script>
<?php include "{$_SERVER['DOCUMENT_ROOT']}/include/footer.php" ?>
