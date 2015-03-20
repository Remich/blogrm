<?php

	/**
	*	Copyright 2010-2014 René Michlke
	*/

	require_once('init_system.php');
	
	// Make the data, which comes from the visitor, save to handle:
	$request = array_merge($_GET, $_POST);
	$request['files'] = $_FILES;
	$request = Sanitize::trim($request);
	
	if(isset($request['item'])) {
		switch($request['item']) {
			
			case 'editor':

				$bouncer = new Auth();
				$_SESSION['editor'] = !(isset($_SESSION['editor']) ? $_SESSION['editor'] : false);
				break;
			case 'admin-panel':
				$_SESSION['admin-panel'] = !(isset($_SESSION['admin-panel']) ? $_SESSION['admin-panel'] : false);
				break;
			case 'sortable':

				$bouncer = new Auth();
				$_SESSION['sortable'] = !(isset($_SESSION['sortable']) ? $_SESSION['sortable'] : false);	
				break;	
			
		}
		
	}
	

	header('Location: '. ($_SESSION['url_bookmarks'] ? $_SESSION['url_bookmarks'] : 'index.php'));
?>
