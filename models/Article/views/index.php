<?php foreach($this->_['data'] as $key => $item) { ?>
<article id="a<?php echo $item['id']; ?>">
	<header>
		<h1><a href="index.php?page=post&id=<?php echo $item['id']; ?>" class="editable" model="Article" model_id="<?php echo $item['id']; ?>" model_key="title"><?php echo $item['title']; ?></a></h1> 
		<span class="post-subtitle"><time datetime="<?php echo $item['a_date']; ?>"><?php echo $item['a_date']; ?></time> | <span title="View all posts in Uncategorized" rel="category" class="editable" model="Article" model_id="<?php echo $item['id']; ?>" model_key="categories"><?php echo $item['categories']; ?></span> | <a href="">0 Comments</a></span>
	</header>
	<section>
		<div class="editable" model="Article" model_id="<?php echo $item['id']; ?>" model_key="content">
			<?php  echo ($item['content'] != "") ? $item['content'] : "Lorem Ipsum"; ?>
		</div>
	</section>
	<footer> </footer>
</article>
<?php } ?>
