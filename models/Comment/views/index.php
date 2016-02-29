<?php foreach($this->_['data'] as $key => $item) { ?>
<section class="comment">
<article id="comment-<?php echo $item['id']; ?>" model="Comment">
	<header>
		<h1><span><?php echo ($item['mail']) ? ('<a href="mailto:' . $item['mail'] . '">' . $item['author'] . '</a>') : ($item['author']) ?><?php echo ($item['www']) ? (' [ <a href="">www</a> ]') : ("") ?></span> wrote on the <span><time datetime="<?php echo $item['c_date']; ?>"><?php echo $item['c_date']; ?></time></span> 
		</h1> 
	</header>
	<div class="editable">
		<p><?php  echo ($item['comment'] != "") ? $item['comment'] : "Lorem Ipsum"; ?></p>
	</div>
</article>
</section>
<?php } ?>
