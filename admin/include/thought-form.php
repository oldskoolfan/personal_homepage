<form action="include/thought-form-handler.php" method="post">
<fieldset>
	<legend>Record a Thought</legend>
	<input type="hidden" name="id" value="<?=isset($id) ? $id : ''?>"/>
	<label>Title:<input type="text" name="title" value="<?=isset($title) ? $title : ''?>"/></label>
	<label for="content">Content</label>
	<textarea id="content" name="content"><?=isset($content) ? $content : ''?></textarea>
	<input type="submit" value="Save"/>
</fieldset>
</form>
