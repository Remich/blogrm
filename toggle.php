<?php

	/**
	*	Copyright 2010-2014 René Michlke
	*/

	error_reporting(E_ALL);

	// Tell PHP that we're using UTF-8 strings until the end of the script
	mb_internal_encoding('UTF-8');
	
	// Tell PHP that we'll be outputting UTF-8 to the browser
	mb_http_output('UTF-8');
	mb_http_input('UTF-8');
	header("Content-Type: text/html; charset=UTF-8");
	
	session_start();
	
	spl_autoload_register(function ($class) {
		require_once 'classes/' . $class . '.class.php';
	});

	
	// Make the data, which comes from the visitor, save to handle:
	$request = array_merge($_GET, $_POST);
	$request['files'] = $_FILES;
	$request = Sanitize::trim($request);
	
	if(isset($request['item'])) {
		switch($request['item']) {
			
			case 'editor':
				$_SESSION['editor'] = !(isset($_SESSION['editor']) ? $_SESSION['editor'] : false);
				break;
			case 'sortable':
				$_SESSION['sortable'] = !(isset($_SESSION['sortable']) ? $_SESSION['sortable'] : false);	
				break;	
			
		}
		
	}
	
	header("Location: ".$_SERVER['HTTP_REFERER']);
?>
