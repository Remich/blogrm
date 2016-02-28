<?php

	/**
	*	Copyright 2010-2014 René Michlke
	*/

	require_once('inc.init_system.php');
	
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
		}
		
	}

	header('Location: '. ($_SESSION['currentURL'] ? $_SESSION['currentURL'] : 'index.php'));
?>
