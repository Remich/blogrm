<ul>
	<?php foreach($this->_['data'] as $key => $item) { ?>
	<li>
		<a href="ajax.php?action=load&id=realfolder&folder=<?php echo $item; ?>"><?php echo $item; ?></a>
	</li>	
	<?php } ?>
</ul>
