<?php foreach($this->_['data'] as $key => $item) { ?>
<article id="article-<?php echo $item['id']; ?>" model="Article">
	<header>
		<h1><a href="index.php?page=post&id=<?php echo $item['id']; ?>" class="editable" model_key="title"><?php echo $item['title']; ?></a></h1> 
		<span class="post-subtitle"><time datetime="<?php echo $item['a_date']; ?>"><?php echo $item['a_date']; ?></time> | <span rel="category" class="editable" model_key="tags"><?php echo $item['tags']; ?></span> | <a href="index.php?page=post&id=<?php echo $item['id']; ?>#comments"><?php echo $item['no_of_comments']; ?> Comments</a></span>
	</header>
	<section>
		<div class="editable" model_key="content">
			<?php  echo ($item['content'] != "") ? $item['content'] : "Lorem Ipsum"; ?>
		</div>
	</section>
	<footer> </footer>
</article>
<?php } ?>
