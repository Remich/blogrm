<?php foreach($this->_['data'] as $key => $item) { ?>
<article id="a<?php echo $item['id']; ?>">
	<header>
		<h3><span><?php echo ($item['www']) ? ('<a href="' . $item['www'] . '">' . $item['author'] . '</a>') : ($item['author']) ?></span> wrote on the <span><time class="post-subtitle" datetime="<?php echo $item['c_date']; ?>"><?php echo $item['c_date']; ?></time>:</span> </h3> </header>
	<div class="editable" model="Article" model_id="<?php echo $item['id']; ?>" model_key="content">
		<section>
			<p><?php  echo ($item['comment'] != "") ? $item['comment'] : "Lorem Ipsum"; ?></p>
		</section>
	</div>
</article>
<?php } ?>
