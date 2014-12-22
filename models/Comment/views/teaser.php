<?php foreach($this->_['data'] as $key => $item) { ?>
<article id="a<?php echo $item['id']; ?>">
	<header>
		<h1><a href="index.php?page=post&id=<?php echo $item['id']; ?>" class="editable" model="Article" model_id="<?php echo $item['id']; ?>" model_key="title"><?php echo $item['title']; ?></a></h1> 
	</header>
	<div class="editable" model="Article" model_id="<?php echo $item['id']; ?>" model_key="content">
		<p>
		<?php  echo ($item['content'] != "") ? $item['content'] : "Lorem Ipsum"; ?>
		</p>
	</div>
</article>
<?php } ?>
