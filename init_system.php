<?php

	/**
	*	Copyright 2010-2015 René Michlke
	*/

	error_reporting(E_ALL);

	// Tell PHP that we're using UTF-8 strings until the end of the script
	mb_internal_encoding('UTF-8');
	
	// Tell PHP that we'll be outputting UTF-8 to the browser
	mb_http_output('UTF-8');
	mb_http_input('UTF-8');
	header("Content-Type: text/html; charset=UTF-8");
	
	require_once("protected/Config.inc.php");
	require_once("helpers.inc.php");

	session_name(Config::getOption("session_name"));
	session_start();
	
	spl_autoload_register(function ($class) {
		require_once  Config::getOption("path_abs") . 'classes/' . $class . '.class.php';
	});


?>