<?php
	include "{$_SERVER['DOCUMENT_ROOT']}/include/header.php";
	include "{$_SERVER['DOCUMENT_ROOT']}/include/get-albums.php";
	include "{$_SERVER['DOCUMENT_ROOT']}/include/nav.php";
?>
<section id="album-list">
<h1>My Music</h1>

<?php foreach($albums as $album): ?>
	<article>
		<h3>
			<span><?=$album['album_title']?></span>
			<span><?=$album['release_year']?></span>
		</h3>
		<?php foreach($album['songs'] as $song): ?>
			<div class="song">
				<span><?=$song['song_title']?></span>
				<audio controls>
					<source src="/assets/songs/<?=$song['filename']?>" type="audio/mpeg">
				</audio>
			</div>
		<?php endforeach; ?>
		<p><span>Credits:<br></span><?=$album['credits']?></p>
	</article>
<?php endforeach; ?>
</section>

<?php include "{$_SERVER['DOCUMENT_ROOT']}/include/footer.php" ?>
