<?php
	session_start();
	$root = $_SERVER['DOCUMENT_ROOT'];
	include "$root/include/header.php";
	include "$root/include/db-connect.php";
	$albums = $con->query('select * from albums');
?>
<h1>Music Admin</h1>
<section id="music_section">

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
	<form action="include/album-form-handler.php" method="post">
	<fieldset>
		<legend>Create a new album</legend>
		<label>Title:<input type="text" name="title"/></label>
		<label>Release Year:<input type="number" min="1900" max="<?=date('Y')?>" name="year"/></label>
		<label for="description">Description:</label>
		<textarea id="description" name="description"></textarea>
		<label for="credits">Credits:</label>
		<textarea id="credits" name="credits"></textarea>
		<input type="submit" value="Save"/>
	</fieldset>
	</form>
</div>
<button id="song_form_btn">Upload a song</button>
<div id="song_form">
	<form action="include/song-form-handler.php" method="post" enctype="multipart/form-data">
	<fieldset>
		<legend>Upload a song</legend>
		<label>Title<input type="text" name="title"/></label>
		<label>Album
			<select name="album">
				<option></option>
				<?php while($album = $albums->fetch_object()): ?>
					<option value="<?=$album->album_id?>"><?=$album->album_title?></option>
				<?php endwhile; $albums->data_seek(0); ?>
			</select>
		</label>
		<label>Recorded date:
			<input id="recorded_date" type="text" name="date"/>
		</label>
		<label for="credits">Credits:</label>
		<textarea id="credits" name="credits"></textarea>
		<label>File:<input type="file" name="song"/></label>
		<input type="submit" value="Save"/>
	</fieldset>
	</form>
</div>
<br>
<!-- albums -->
<h3>My Albums</h3>
<div class="albums">
<?php while($album = $albums->fetch_object()): ?>
	<div class="album-link">
		<a href="album.php?id=<?=$album->album_id?>"><?=$album->album_title?></a>
	</div>
<?php endwhile; ?>
</div>
</section>
<script src="assets/js/main.js"></script>
<?php include "{$_SERVER['DOCUMENT_ROOT']}/include/footer.php" ?>
