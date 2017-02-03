<?php
	include "{$_SERVER['DOCUMENT_ROOT']}/include/header.php";
	include "{$_SERVER['DOCUMENT_ROOT']}/include/get-thoughts.php";


?>
<section id="thought-list">
<h1>My Thoughts</h1>

<?php foreach($thoughts as $thought): ?>
	<?php $date = date_create($thought['date_modified']); ?>
	<article>
		<h3>
			<span><?=$thought['thought_title']?></span>
			<span><small>Posted <?=date('l, F jS, Y \a\t h:i a', $date->getTimestamp())?></small></span>
		</h3>
		<div class="content"><?=$thought['thought_text']?></div>
	</article>
<?php endforeach; ?>
</section>

<?php include "{$_SERVER['DOCUMENT_ROOT']}/include/footer.php" ?>
