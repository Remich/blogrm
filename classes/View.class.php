<?php

	/**
	*	Copyright 2010-2014 René Michalke
	*/
	
	
	/***
	* class View
	*
	* This is basically the Template Class.
	* Providing methods to pass data from the Controller to the View and to load templates
	*/
	class View {
		
		// Contains the data, which shall be embedded in the template 
		private $_ = array();
		private $_templateDir = null;
		private $_template = "index";
		
		public function __construct() {
			$this->_templateDir = "themes/".Config::getOption("theme")."/";
		}
		
		// Method to assign data to the template
		public function assign($key, $value) {
			$this->_[$key] = $value;
		}
		
		// Setting the name of the template
		public function setTemplate($template = 'index') {
			$this->_template = $template;	
		}
		
		public function setTemplateDir($folder) {
			$this->_templateDir = $folder;	
		}
		
		
		/**
		* Load and return the template file
		*
		* @return string, Ouput of template
		*/
		public function loadTemplate() {
		
			// Creating path to template file & check if template exists
			
			$file = $this->_templateDir.$this->_template .'.php';
								
			if ( file_exists($file) ) {
			
				// The Output of the script is being stored in a buffer
				ob_start();
				
				include $file;
				$output = ob_get_contents();
				ob_end_clean();
				
				return $output;
				
			} else {
			
				return 'Error: Could not find template: '.$file;
				
			}
			
		}  // <!-- end function ’loadTemplate()’ -->
		
	}  // <!-- end class ’View’ -->   
?>
