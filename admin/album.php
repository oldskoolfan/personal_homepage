<?php
	session_start();
	$root = $_SERVER['DOCUMENT_ROOT'];
	include "$root/include/header.php";
	include "$root/include/db-connect.php";
	$stmt = $con->prepare('select a.*, s.song_id, s.song_title
		from albums as a left join songs as s
		using(album_id) where a.album_id = ?');
	$stmt->bind_param('i', $id);

	// check for id, redirect if not found
	if (!isset($_GET['id'])) {
		header('Location: music.php');
	}
	$id = $_GET['id'];
	$success = $stmt->execute();
	if ($success) {
		$result = $stmt->get_result();
		$album = $result->fetch_object();
		$result->data_seek(0);
		$title = $album->album_title;
		$desc = $album->album_desc;
		$year = $album->release_year;
		$credits = $album->credits;
	} else {
		echo '<div class="error">Error fetching album</div>';
	}
?>
<h1>Album Admin</h1>
<a href="music.php">BACK</a>
<section class="form-wrapper">
<?php include 'include/album-form.php' ?>
</section>
<table id="songs">
	<tr><th>Title</th><th>Action</th></tr>
	<?php while($song = $result->fetch_object()):?>
		<?php if (isset($song->song_id)):?>
		<tr>
			<td><?=$song->song_title?></td>
			<td><a href="song.php?id=<?=$song->song_id?>">EDIT</a></td>
		</tr>
		<?php endif; ?>
	<?php endwhile; ?>
</table>
<?php include "{$_SERVER['DOCUMENT_ROOT']}/include/footer.php" ?>
