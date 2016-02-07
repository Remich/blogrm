<section id="News">
<?php if(sizeof($this->_['data']['content']) == 0) echo "No entries found"; else { ?>
<?php foreach($this->_['data']['content'] as $key => $item) { ?>
<?php echo $item; ?>
<?php } ?>
<?php echo $this->_['data']['status']['flipping']; ?>
<?php } ?>
</section>
