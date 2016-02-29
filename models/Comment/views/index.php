<?php foreach($this->_['data'] as $key => $item) { ?>
<article id="comment-<?php echo $item['id']; ?>" model="Comment" class="comment">
	<header>
		<div class="author"><?php echo $item['author']; ?></div>
		<div class="date"><strong>date:</strong> <?php echo $item['c_date']; ?></div>
		<?php if(@$item['mail']) { ?><div class="mail"><strong>mail:</strong> <?php echo $item['mail']; ?></div><?php } ?>
		<?php if(@$item['www']) { ?><div class="www"><strong>www:</strong> <?php echo $item['www']; ?></div><?php } ?>
	</header>
	<div class="comment-right">
		<div class="corner"></div>
		<p class="editable">
		<?php echo $item['comment']; ?>
		</p>
	</div>
</article>
<?php } ?>
