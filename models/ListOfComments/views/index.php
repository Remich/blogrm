<section id="ListOfComments">
<?php if(sizeof($this->_['data']['content']) == 0) echo "No comments found"; else { ?>
<?php foreach($this->_['data']['content'] as $key => $item) { ?>
<?php echo $item; ?>
<?php } ?>
<?php echo $this->_['data']['status']['flipping']; ?>
<?php } ?>
</section>
