<h1>List of Contents</h1>
<?php if(sizeof($this->_['data']) == 0) echo "No entries found"; else { ?>
<ul>
	<?php 
	foreach($this->_['data'] as $key => $item) {
	
		/*$level = 0;
		$level_max = 0;
		
		foreach($this->_['data'] as $key => $item) { 
	
		$anzahl_dots = 0;
	
		$b = 0;
		while($item['title'][$b] != " ") {
			if($item['title'][$b++] == '.')
				$anzahl_dots++;
		}
		
		if($level < $anzahl_dots)
			echo '<ul>';

		

		if($level > $anzahl_dots)
			for($z = 0; $z < $level - $anzahl_dots; $z++) echo '</ul>';
		
		
		$level = $anzahl_dots;
		if($level > $level_max)
			$level_max = $level;*/
			
	?>	
	<li id="<?php echo $item['id']; ?>">
	<a href="#a<?php echo $item['id']; ?>"><?php echo $item['title']; ?></a>
	</li>
	<?php
 } ?>
<?php //for($a = 0; $a < $level_max; $a++) echo "</ul>"; ?>
</ul>
<?php } ?>