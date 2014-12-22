<?php

	/**
	*	Copyright 2010-2013 René Michalke
	*
	*	This file is part of RM Internet Suite.
	*/
	require_once('classes/View.class.php');
	
	/***
	* class NavigationNavigationFromFolder
	*
	* This class loads the Navigation 
	*/
	class NavigationFromFolder extends ModelList {
		
		// path to the modules  
		private $_path = null;
		private $_navigation = null;
		protected $_name = "NavigationFromFolder";
		
		public function __construct( $path) {
		
			$this->_path = $path;
			$this->_data = $this->getFiles();
			asort($this->_data);
		}
		
	
		
		private function getFiles() {
		
			$ar = array();
			
			if(@$handle = opendir($this->_path)) {
			
				while (false !== ($file = readdir($handle)))
					if ($file != "." && $file != "..") $ar[] = $file;
					
				closedir($handle);
				
				return $ar;
			
			} else die('Error: Could not open path "' . $this->_path . '"');
		
		}
		
	}  // <!-- end class ’NavigationFromFolder’ -->   
?>
