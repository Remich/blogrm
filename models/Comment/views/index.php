<?php foreach($this->_['data'] as $key => $item) { ?>
<article id="comment-<?php echo $item['id']; ?>" model="Comment" class="comment">
	<header>
		<div class="author"><span class="editable" model_key="author"><?php echo $item['author']; ?></span></div>
		<div class="date"><strong>date:</strong> <span><?php echo $item['c_date']; ?></span></div>
		<?php if(@$item['mail']) { ?><div class="mail"><strong>mail:</strong> <span class="editable" model_key="mail"><?php echo $item['mail']; ?></span></div><?php } else { ?><span class="editable" model_key="mail" style="visibility: hidden"></span><?php } ?>
		<?php if(@$item['www']) { ?><div class="www"><strong>www:</strong> <span class="editable" model_key="www"><?php echo $item['www']; ?></span></div><?php } else { ?><span class="editable" model_key="www" style="visbility: hidden"></span><?php } ?>
		<span class="editable" model_key="a_id" style="visibility: hidden"><?php echo $item['a_id']; ?></span>
	</header>
	<div class="comment-right">
		<div class="corner"></div>
		<p class="editable" model_key="comment">
		<?php echo $item['comment']; ?>
		</p>
	</div>
</article>
<?php } ?>
