<form action="include/album-form-handler.php" method="post">
<fieldset>
	<legend>Create a new album</legend>
	<input type="hidden" name="id" value="<?=isset($id) ? $id : ''?>"/>
	<label>Title:<input type="text" name="title" value="<?=isset($title) ? $title : ''?>"/></label>
	<label>Release Year:<input type="number" min="1900" max="<?=date('Y')?>" name="year"
		value="<?=isset($year) ? $year : ''?>"/></label>
	<label for="description">Description:</label>
	<textarea id="description" name="description"><?=isset($desc) ? $desc : ''?></textarea>
	<label for="credits">Credits:</label>
	<textarea id="credits" name="credits"><?=isset($credits) ? $credits : ''?></textarea>
	<input type="submit" value="Save"/>
</fieldset>
</form>
