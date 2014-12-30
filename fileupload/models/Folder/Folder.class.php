<?php

	/**
	 * @file
	 * Copyright 2010-2014 René Michalke.
 	 */
	
	require_once('interfaces/iDBContentStatic.interface.php');
	
	class Folder extends ModelList {
		
		protected $_name = "Folder";
		protected $_templateDir = "fileupload/models/";
		private $_dir = null;
		
		/**
		* Constructor
		*
		*/
		function __construct($dir) {
			$this->_dir = $dir;
		} 
		
		
		public function load() {
			$scan = scandir($this->_dir);
			unset($scan[0]);
			unset($scan[1]);
			$this->_data = $scan;
		}
		
		public function save() {
		
		}

	} // <!-- end class ’Controller’ -->
?>
