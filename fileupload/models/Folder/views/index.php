<div id="uploaded_files">
<?php foreach($this->_['data'] as $key => $item) { ?>
<a href="upload/<?php echo $item; ?>" name="<?php echo $item; ?>"><?php echo $item; ?></a><br>
<?php } ?>
</div>