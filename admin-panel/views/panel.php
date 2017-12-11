<div id="admin-panel" class="panel_default" state="false">
	<section>	 
		<?php foreach($this->_['active_plugins'] as $item) {
			echo '<a id="switch_'.$item['key'].'" href="toggle.php?item='.$item['key'].'">'.$item['name'].'</a>';
		} ?><a id="logout" href="admin-panel/index.php?page=logout">Logout &#8594;</a>
	</section>
</div>
