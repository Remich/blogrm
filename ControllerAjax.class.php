<?php

	/**
	*	Copyright 2010-2014 René Michalke.
	*/
	class ControllerAjax extends ControllerBase {

		/**
		* Running the actual application
		*/
		public function control() {
			
			switch(@$this->_request['action']) {
		
				default:	
					die('This is the default action of the Ajax Controller');
				break; // <!-- end case ’default’ --> 

				case 'load':
				
						$url = $_SESSION['currentURL'];
						$aUrl = Url::explode($url);
							
				
						switch ($this->_request['id']) {
				
							default:
								die('Error: No $this->request["id"] not set.');
							break;

							case 'page':
								$this->isInRequest( 'jump' );
									
								$url = Url::updateParams( $url, array('jump' => $this->_request['jump'] ) );
							break;

						}

						$_SESSION['currentURL'] = $url;
							
						if(isset($this->request['redirect']) && $this->request['redirect'] == 0)
							die($_SESSION['currentURL']);
						else
							header('Location: '.$_SESSION['currentURL']);
				break;
				
				
				case 'save':				
					$bouncer = new Auth();

					$this->isInRequest( array( 'data') );

					foreach($this->_request['data'] as $item) {

						if (!isset($item['id']) ||
							!isset($item['model']) ||
							!isset($item['key']) ||
							!isset($item['value']) ) {
							die('#f');
						}

						$item['id'] = filter_var($item['id'], FILTER_VALIDATE_INT);
						$item['model'] = Sanitize::FileName($item['model']);
						$item['value'] = Sanitize::RemoveTagsFromPre($item['value']);
						
						require_once("models/".$item['model']."/".$item['model'].".class.php");
						$model = new $item['model'](array('id'=>$item['id']));
						$model->set(array($item['key'] => $item['value']));
						$model->save();	
					}
						
					die("#t");
					break;
				
				case 'delete':
					$bouncer = new Auth();

					$this->isInRequest( array( 'id', 'model') );

					$this->_request['id'] = filter_var($this->_request['id'], FILTER_VALIDATE_INT);
					$this->_request['model'] = Sanitize::FileName($this->_request['model']);
						
					require_once("models/".$this->_request['model']."/".$this->_request['model'].".class.php");
					$model = new $this->_request['model'](array('id'=>$this->_request['id']));
						
					die($model->delete());
					break;
						
				case 'newfile':
					$bouncer = new Auth();

					$this->isInRequest( array( 'model' ) );
						
					$this->_request['model'] = Sanitize::FileName($this->_request['model']);
					require_once("models/".$this->_request['model']."/".$this->_request['model'].".class.php");
					$model = new $this->_request['model']();	
					
					die($model->display('Article'));				
					break;
					
				case 'getPlainHTML':
					$this->isInRequest( array('value') );
					
					die(htmlentities($this->_request['value']));
					
					break;
					
			} // <!-- end ’switch(@$this->_request['page'])’ -->
		
		} // <!-- end function ’control()’ -->
		
	} // <!-- end class ’ControllerAjax’ -->   
?>
