<?php

	/**
	*	Copyright 2010-2014 René Michlke
	*/

	require_once('init_system.php');
	
	require_once('Controller.class.php');
	
	// Make the data, which comes from the visitor, save to handle:
	$request = array_merge($_GET, $_POST);
	$request['files'] = $_FILES;
	$request = Sanitize::trim($request);
	
	$controller = new Controller($request);
	     	 
	// Run the control
	$controller->control();
	
	// Display content
	echo $controller->display();
	
?>
