<?php foreach($this->_['data'] as $key => $item) { ?>
	<tr id="<?php echo $item['id']; ?>">
		<td class="select">
			<input type="checkbox" name="selected" model="Bookmark" model_id="<?php echo $item['id']; ?>">
		</td>
		<td class="hits" item_id="<?php echo $item['id']; ?>">
			<?php echo $item['hits']; ?>
		</td>
		<?php if(@!$_SESSION['editor']) { ?>
		<td item_id="<?php echo $item['id']; ?>">
			<a href="index.php?page=go&id=<?php echo $item['id']; ?>" class="editable" model="Bookmark" model_id="<?php echo $item['id']; ?>" model_key="content" id="<?php echo $item['id']; ?>" class="title<?php if($item['hidden']) echo ' clHidden'; ?>"><?php echo ($item['title'] == "") ? $item['url'] : $item['title']; ?></a>	
		</td><?php } else { ?>
		<td item_id="<?php echo $item['id']; ?>" class="editable" model="Bookmark" model_id="<?php echo $item['id']; ?>" model_key="content">
			<a href="<?php echo $item['url']; ?>"><?php echo ($item['title'] == "") ? $item['url'] : $item['title']; ?></a>	
		</td>
		<?php } ?>
		
		<td class="editable" model="Bookmark" model_id="<?php echo $item['id']; ?>" model_key="categories" item_id="<?php echo $item['id']; ?>">
			#<?php echo $item['categories']; ?>
		</td>
		<td class="date" item_id="<?php echo $item['id']; ?>">
			<?php echo date_format(date_create($item['date']), 'Y-m-d'); ?>
		</td>
		<td class="last_visit" item_id="<?php echo $item['id']; ?>">
			<?php echo date_format(date_create($item['last_hit']), 'Y-m-d'); ?>
		</td>
	</tr>
<?php } ?>
