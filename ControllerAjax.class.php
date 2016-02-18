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
					// Misc::dump($this->_request['data']);
					// die();

					foreach($this->_request['data'] as $key => $item) {
						$tmp = array();
						foreach($item['data'] as $item2) {
							$tmp[ $item2['key'] ] = $item2['val'];
							$this->_request['data'][$key]['data'] = $tmp;
						}
					}

					foreach($this->_request['data'] as $item) {

						if (!isset($item['id']) ||
							!isset($item['model']) ||
							!isset($item['data']) ) {
							die('#f');
						}

						$model = Sanitize::FileName($item['model']);
						unset($item['model']);

						foreach($item['data'] as $key2 => $item2) {
							$item['data'][$key2] = Sanitize::RemoveTagsFromPre($item2);
						}

						$id = explode("-", $item['id'])[1];
						$item['data']['id'] = filter_var($id,FILTER_VALIDATE_INT);
						unset($item['id']);

						// Misc::dump($item);

						// TODO: Massive security hole!!!
						require_once("models/".$model."/".$model.".class.php");
						$model = new $model($item);
						$model->saveEntry();	
					}
						
					die("#t");
					break;
				
				case 'delete':
					$bouncer = new Auth();

					$this->isInRequest( array( 'id', 'model') );

					$this->_request['id'] = explode("-", $this->_request['id'])[1];
					$this->_request['id'] = filter_var($this->_request['id'], FILTER_VALIDATE_INT);
					$this->_request['model'] = Sanitize::FileName($this->_request['model']);
						
					// TODO: Massive security hole!!!
					require_once("models/".$this->_request['model']."/".$this->_request['model'].".class.php");
					$model = new $this->_request['model'](array('id'=>$this->_request['id']));
						
					die($model->deleteEntry());
					break;
						
				case 'newfile':
					$bouncer = new Auth();

					$this->isInRequest( array( 'model' ) );
						
					$this->_request['model'] = Sanitize::FileName($this->_request['model']);
					// TODO: Massive security hole!!!
					require_once("models/".$this->_request['model']."/".$this->_request['model'].".class.php");
					$model = new $this->_request['model']();	
					$model->saveEntry();
					$model->loadEntry();
					
					die($model->display());				
					break;
					
				case 'getPlainHTML':
					$this->isInRequest( array('value') );
					
					die(htmlentities($this->_request['value']));
					
					break;
					
			} // <!-- end ’switch(@$this->_request['page'])’ -->
		
		} // <!-- end function ’control()’ -->
		
	} // <!-- end class ’ControllerAjax’ -->   
?>
