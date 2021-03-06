<?php

	/**
	*	Copyright 2010-2015 René Michlke
	*
	*	This file is part of RM Internet Suite.
	*/

	/**
	* class Auth
	*
	*/
	class Auth {       
	
		public function __construct($display_login_form = false) {
		
			if(!Auth::isLoggedIn('userhash')) {
			
				if(!Auth::isCookie('userhash')) {
				
					$login = new View();
					$login->setTemplateDir( Config::getOption("path_abs") . "admin-panel/views/");
					if ($display_login_form === true) {
						$login->setTemplate('login');
					} else {
						$login->setTemplate('access_denied');
					}
					die($login->loadTemplate());	

				} else {
					
					$query = "SELECT *
							  FROM users
							  WHERE hash = :hash";
					$params = array(
						':hash' => $_COOKIE['userhash']
					);
					
					$data = DB::get($query, $params);
					if(sizeof($data) == 1) {
						$_SESSION['userhash'] = $_COOKIE['userhash'];
						$_SESSION['uid'] = $data[0]['id'];
					} elseif(sizeof($data) > 1)
						die('Error: Hash Collision detected');
					else {
						setcookie('userhash', 'invalid', time()-60*60*24*30);
						$login = new View();
						$login->setTemplateDir( Config::getOption("path_abs") . "admin-panel/views/");
						$login->setTemplate('login');
						die($login->loadTemplate());
					}
						
				} 
				
			}
			
		}
	 
        public static function isLoggedIn($str){  
        
            if(isset($_SESSION[$str])) return 1;    
            else 0;
            
        }       
        
        private static function isCookie($str) {
        
        	if(isset($_COOKIE[$str])) return 1;
        	else 0;
        
        }
        
        public static function login($username, $password) {
        	
			$msg = 'Error: Wrong Username and/or Password';
			$msg2 = 'Error: Please wait 3 seconds until next login attempt.';


        	// Check if username exists
        	$query = 'SELECT * FROM users WHERE username = :username LIMIT 1';
			$params = array(
				':username' => $username
			);
			$data = DB::getOne($query, $params);
			
			if(sizeof($data) === 0) {
				return($msg);
			}



			// Bruteforce Protection
			switch(Config::getOption("db_type")) {
				case "mysql": 
					$query = 'SELECT * FROM users WHERE username = :username AND last_login_attempt < NOW() - INTERVAL 3 Second';
				break;
			
				case "sqlite":
					$query = "SELECT * FROM users WHERE username = :username AND last_login_attempt <  datetime('now', '-3 seconds')";
				break;
			}

			$data = DB::getOne($query, $params);

			if (sizeof($data) === 0) {
				return($msg2);
			}

			switch(Config::getOption("db_type")) {
				case "mysql": 
					$query = 'UPDATE users SET last_login_attempt = NOW() WHERE username = :username';
				break;
			
				case "sqlite":
					$query = "UPDATE users SET last_login_attempt = datetime('now') WHERE username = :username";
				break;
			}

			DB::execute($query, $params);
			
			if($data['password'] != $password) {
				 die($msg);
			} else {
				$_SESSION['userhash'] = $data['hash'];
				$_SESSION['uid'] = $data['id'];
				setcookie('userhash', $data['hash'], time()+60*60*24*30);
				return('1');
			}
        
        }
        
        public static function logout() {
        
        	session_destroy();
			setcookie('userhash', 0, time()-60*60*24*365);
       
        }
		
	} // <!-- end class ’Auth’ -->
	
?>
