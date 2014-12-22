<?php

	/**
	*	Copyright 2010-2014 René Michalke.
	*/
	class Controller extends ControllerBase {

		private $_view = null; // our view object
		private $_header = null;
		private $_footer = null;
		
		/**
		* Constructor
		*
		* @param Array $request, merged array of $_GET & $_POST & $_FILES
		*/
		public function __construct($request) {
			
			parent::__construct($request);
			$this->_view = new View();

		} // <!-- end function ’__construct()’ -->
		
		/**
		* Running the actual application
		*/
		public function control() {
			
			switch(@$this->_request['page']) {
		
				default:
				
					break; // <!-- end case ’default’ --> 
					
				case "file_upload":
					include("fileupload/index.inc.php");
				break;
				
				case "files_display":

					require_once("fileupload/models/Folder/Folder.class.php");
					$folder = new Folder("upload");
					$folder->load();
					$this->_view->assign('Folder', $folder->display());
					$this->_view->setTemplate('choose_link');
					$this->_view->setTemplateDir('editor/views/');
					
					break;
			
			} // <!-- end ’switch(@$this->request['page'])’ -->
			
			
		
		
		} // <!-- end function ’control()’ -->
		
		/**
		* Displaying the content
		*
		* @return String, the generated html code
		*/
		public function display() {
			$this->_footer .= DB::getNumberOfQueries()." queries executed, ";
			$this->_footer .= DB::getNumberOfConnections()." connections used";
			
			$this->_view->assign('header', $this->_header);
			$this->_view->assign('footer', $this->_footer);
			return $this->_view->loadTemplate();
		} // <!-- end function ’display()’ -->
		
	} // <!-- end class ’Controller’ -->   
?>
