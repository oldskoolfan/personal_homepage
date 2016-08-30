<form action="include/song-form-handler.php" method="post" enctype="multipart/form-data">
<fieldset>
	<legend>Upload a song</legend>
	<label>Title<input type="text" name="title"/></label>
	<label>Album
		<select name="album">
			<option></option>
			<?php if($albums): ?>
				<?php while($album = $albums->fetch_object()): ?>
					<option value="<?=$album->album_id?>"><?=$album->album_title?></option>
				<?php endwhile; $albums->data_seek(0); ?>
			<?php endif; ?>
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
