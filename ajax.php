<?php

	/**
	*	Copyright 2010-2014 René Michlke
	*/
	require_once('init_system.php');

	require_once('ControllerAjax.class.php');
	
	$request = array_merge($_GET, $_POST);
	$request['files'] = $_FILES;
	$options = array("trim", /*"purify", */"addslashes");
	Sanitize::process_array($request, $options);
	
	$controller = new ControllerAjax($request);

	// Run the control
	$controller->control();
	
	// Display content
	echo $controller->display();
	
?>
