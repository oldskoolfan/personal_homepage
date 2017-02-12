<?php
	include "{$_SERVER['DOCUMENT_ROOT']}/include/header.php";
	include "{$_SERVER['DOCUMENT_ROOT']}/include/get-thoughts.php";
	include "{$_SERVER['DOCUMENT_ROOT']}/include/nav.php";
?>
<h1>My Thoughts</h1>
<section id="thought-list">
<?php foreach($thoughts as $thought): ?>
	<?php $date = date_create($thought['date_modified']); ?>
	<article>
		<h3 class="clearfix">
			<span><?=$thought['thought_title']?></span>
			<span><small>Posted <?=date('l, F jS, Y \a\t h:i a', $date->getTimestamp())?></small></span>
		</h3>
		<div class="content"><?=$thought['thought_text']?></div>
	</article>
<?php endforeach; ?>
</section>

<?php include "{$_SERVER['DOCUMENT_ROOT']}/include/footer.php" ?>
