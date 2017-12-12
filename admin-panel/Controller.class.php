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
				case "show_panel":
					$this->_view->setTemplate('panel');

					// check if user is loggend in, and therefore has the required privileges
					$bouncer = new Auth(true);

					// assign active_plugins, so we can only show the relevant switches
					$this->_view->assign('active_plugins', Config::getActivePluginSwitches());

					$this->_view->assign('is_logged_in', Auth::isLoggedIn('userhash') );
					break; // <!-- end case ’default’ --> 
					
				case "login":
					$this->_view->setTemplateDir(Config::getOption("path_abs") . "admin-panel/views/");
					$this->_view->setTemplate("login");
				break;

				case "login_step_2":
					$this->isInRequest( array('username', 'password') );
					die( Auth::login($this->_request['username'], $this->_request['password']) );		
				break;
				
				case "logout":

					Auth::logout();
					header('Location: ../index.php');
					
					break;
			
			} // <!-- end ’switch(@$this->request['page'])’ -->
			
			
		
		
		} // <!-- end function ’control()’ -->
		
		/**
		* Displaying the content
		*
		* @return String, the generated html code
		*/
		public function display() {
			$this->_view->setTemplateDir(Config::getOption("path_abs") . "admin-panel/views/");
			return $this->_view->loadTemplate();
		} // <!-- end function ’display()’ -->
		
	} // <!-- end class ’Controller’ -->   
?>
