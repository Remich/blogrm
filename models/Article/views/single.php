<?php foreach($this->_['data'] as $key => $item) { ?>
<article id="a<?php echo $item['id']; ?>">
	<header>
		<h1><a href="index.php?page=post&id=<?php echo $item['id']; ?>" class="editable" model="Article" model_id="<?php echo $item['id']; ?>" model_key="title"><?php echo $item['title']; ?></a></h1> 
		<span class="post-subtitle"><time datetime="<?php echo $item['a_date']; ?>"><?php echo $item['a_date']; ?></time> | <span title="View all posts in Uncategorized" rel="category" class="editable" model="Article" model_id="<?php echo $item['id']; ?>" model_key="tags"><?php echo $item['tags']; ?></span> | <a href=""><?php echo $item['no_of_comments']; ?> Comments</a></span>
	</header>
	<section>
		<div class="editable" model="Article" model_id="<?php echo $item['id']; ?>" model_key="content">
			<?php  echo ($item['content'] != "") ? $item['content'] : "Lorem Ipsum"; ?>
		</div>
	</section>
	<footer> </footer>
</article>
<section class="comments">
	<h1>Comments</h1>
	<?php echo $item['comments']; ?>
	<div class="clear"></div>
	<h1>Leave a Comment</h1>
	<form action="index.php?page=comment&id=<?php echo $item['id']; ?>" method="post">	
		<dl>
			<dt>
				Author<em>*</em>:
			</dt>
			<dd> 
				<input type="text" name="author" maxlength="255"> 
			</dd>
			<dt>
				Mail:
			</dt>
			<dd>
				<input type="email" name="mail" maxlength="255">
			</dd>
			<dt>
				WWW:
			</dt>
			<dd>
				<input type="url" name="www" maxlength="255">
			</dd>
			<dt>
				Comment<em>*</em>:
			</dt>
			<dd>
				<textarea name="comment"></textarea>
			</dd>
		<input type="submit">
	</form>
</section>
<?php } ?>
