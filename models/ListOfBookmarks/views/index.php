<h1><?php echo $this->_['data']['title']; ?></h1>


<a class="rm_layout" href="javascript:void(window.open('http://localhost/html/mvc-framework/index.php?page=add_bookmark&url='+encodeURIComponent(location.href)+'&title='+encodeURIComponent(document.title)
			,'TimeTableTimer','status=no,directories=no,location=no,resizable=yes,menubar=no,width=650,height=330,toolbar=no'));">Your Favelet</a> | <a href="ajax.php?action=load&id=trashed&trashed=<?php echo $this->_['data']['status']['trashed']; ?>">Trash</a> | <a href="ajax.php?action=emptybin&model=Bookmark">Empty Trash</a><br><br>
			
<?php echo $this->_['data']['status']['flipping']; ?>
<br><br>	
		  		
		  		
 <div id="sorting-dock">

<span id="styling">
		
<a href="ajax.php?action=load&id=order&order=DESC"><?php if($this->_['data']['status']['order'] == 'DESC') { ?><strong><?php } ?>⇣ Descending ⇣<?php if($this->_['data']['status']['order'] == 'DESC') { ?></strong><?php } ?></a> | <a href="ajax.php?action=load&id=order&order=ASC"><?php if($this->_['data']['status']['order'] == 'ASC') { ?><strong><?php } ?>⇡ Ascending ⇡ <?php if($this->_['data']['status']['order'] == 'ASC') { ?></strong><?php } ?></a>


	</span>
</div> <!-- end <div id="sorting-dock"> -->
<table id="bookmarks_table">
	<tr>
		<td>
		</td>
		<td>
			<a href="ajax.php?action=load&id=sort_hits"><?php #if($this->_['sort'] == 'hits') { ?><strong><?php #} ?>Hits<?php #if($this->_['sort'] == 'hits') { ?></strong><?php #} ?></a>
		</td>
		<td>
			<a href="ajax.php?action=load&id=sort_title"><?php #if($this->_['sort'] == 'title') { ?><strong><?php #} ?>Name<?php #if($this->_['sort'] == 'title') { ?></strong><?php #} ?></a>
		</td>
		<td width="24%">
			<a href="ajax.php?action=load&id=sort_tag"><?php #if($this->_['sort'] == 'date') { ?><strong><?php #} ?>Tags<?php #if($this->_['sort'] == 'date') { ?></strong><?php #} ?></a>
		</td>
		<td width="12%">
			<a href="ajax.php?action=load&id=sort_date"><?php #if($this->_['sort'] == 'date') { ?><strong><?php #} ?>Date<?php #if($this->_['sort'] == 'date') { ?></strong><?php #} ?></a>
		</td>
		<td width="12%">
			<a href="ajax.php?action=load&id=sort_last_hit"><?php #if($this->_['sort'] == 'last_hit') { ?><strong><?php #} ?>Last Visit<?php #if($this->_['sort'] == 'last_hit') { ?></strong><?php #} ?></a>
		</td>
	</tr>
	<?php foreach($this->_['data']['content'] as $key => $item) { ?>
	<?php echo $item; ?>
	<?php } ?>
	<tr>
		<td id="check_all" colspan="6" class="white"><input type="checkbox" name="check_all" id="check_all_checkbox"><label for="check_all_checkbox">Check All</label></td>
	</tr>
</table>
<br><br>
<?php echo $this->_['data']['status']['flipping']; ?>
