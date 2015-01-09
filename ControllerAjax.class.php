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
				
						$url = $_SESSION['url_bookmarks'];
						$aUrl = Url::explode($url);
							
				
						switch ($this->_request['id']) {
				
							default:
								die('Error: No $this->request["id"] not set.');
								break;
				
							case 'all_bookmarks':
				
								$url = Url::setParams( $url, array(), array('module', 'search') );
								$url = Url::updateParams( $url, array('type' => 'all') );
				
								break;
				
							case 'trashed':
									
								if(!isset($this->_request['trashed']))
									$this->_request['trashed'] = 0;
								$url = Url::removeParams( $url, array('jump') );
								$url = Url::updateParams( $url, array('trashed' => !$this->_request['trashed']) );
				
								break;
				
							case 'clear_all_filter':
									
								$url = Url::removeParams( $url, array('jump', 'type', 'tag_id', 'month', 'year', 'status') );
								$url = Url::updateParams( $url, array() );
								
								break;
				
							case 'filter_not_tagged':
									
								$url = Url::removeParams( $url, array('jump', 'tag', 'page', 'status') );
								$url = Url::updateParams( $url, array('type' => 'notag') );
				
								break;
				
							case 'filter_tag':
				
								$this->isInRequest( 'tid' );
				
								$url = Url::removeParams( $url, array('jump', 'month', 'year', 'page', 'status') );
								$url = Url::updateParams( $url, array('type' => 'tag', 'tid' => $this->request['tid']) );
									
								break;
				
							case 'filter_month':
				
								$this->isInRequest( array('month', 'year') );
				
								$url = Url::removeParams( $url, array('jump', 'tag', 'page', 'status') );
								$url = Url::updateParams(
										$url,
										array(
												'type' => 'month',
												'month' => $this->_request['month'],
												'year' => $this->_request['year']
										)
								);
				
								break;
				
							case 'filter_year':
				
								$this->isInRequest( 'year' );
				
								$url = Url::removeParams( $url, array( 'jump', 'tag', 'page', 'status') );
								$url = Url::updateParams( $url, array('type' => 'year', 'year' => $this->request['year'] ) );
				
								break;
				
							case 'sort_date':
									
								if(@$aUrl['query_params']['jump'] != 'all')
									$url = Url::removeParams( $url, array('jump') );
									$url = Url::updateParams( $url, array('sort' => 'date') );
				
									break;
				
							case 'sort_title':
									
								if(@$aUrl['query_params']['jump'] != 'all')
									$url = Url::removeParams( $url, array('jump') );
									$url = Url::updateParams( $url, array('sort' => 'title' ) );
				
									break;
				
							case 'sort_hits':
									
								if(@$aUrl['query_params']['jump'] != 'all')
									$url = Url::removeParams( $url, array('jump') );
									$url = Url::updateParams( $url, array('sort' => 'hits' ) );
				
									break;
									
							case 'sort_tag':
									
								if(@$aUrl['query_params']['jump'] != 'all')
									$url = Url::removeParams( $url, array('jump') );
									$url = Url::updateParams( $url, array('sort' => 'tags' ) );
							
									break;
				
							case 'sort_last_hit':
				
								if(@$aUrl['query_params']['jump'] != 'all')
									$url = Url::removeParams( $url, array('jump') );
									$url = Url::updateParams( $url, array('sort' => 'last_hit' ) );
										
									break;
				
							case 'order':
				
								$this->isInRequest( 'order' );
				
								if(@$aUrl['query_params']['jump'] != 'all')
									$url = Url::removeParams( $url, array('jump') );
								$url = Url::updateParams( $url, array('order' => $this->_request['order'] ) );
									
								break;
				
							case 'page':
				
								$this->isInRequest( 'jump' );
									
								$url = Url::updateParams( $url, array('jump' => $this->_request['jump'] ) );
				
								break;
								
							case 'realpage':
									
								$this->isInRequest( 'page' );
								$url = Url::removeParams( $url, array('jump', 'tag_id') );
								$url = Url::updateParams( $url, array('page' => $this->_request['page'] ) );
									
								break;
								
							case 'realfolder':
										
									$this->isInRequest( 'folder' );
									$path = Url::getCurrentPath()."../".$this->_request['folder'];
									//die($location);
									header('Location: '.$path);
									die();
										
									break;
								
							case 'tag':
								
								$this->isInRequest( 'tag_id' );

								$url = Url::removeParams( $url, array('jump') );
								
								$tags = Url::getParams($url);
								if(isset($tags['tag_id'])) 
									$url = Url::updateParams( $url, array('tag_id' => $tags['tag_id'].".".$this->_request['tag_id'] ) );
								else
									$url = Url::updateParams( $url, array('tag_id' => $this->_request['tag_id'] ) );
							
								break;
									
							case 'removetag':
								
								$this->isInRequest( 'tag_id' );
							
								$url = Url::removeParams( $url, array('jump') );
									
								$tags = Url::getParams($url);
								$tags_ar = explode(".", $tags['tag_id']);
								
								foreach($tags_ar as $key => $item)
									if($item == $this->_request['tag_id'])
										unset($tags_ar[$key]);

								if(sizeof($tags_ar)==0) {
									$url = Url::removeParams($url, array('tag_id'));
								} else {
									$tags = implode(".", $tags_ar);
									$url = Url::updateParams( $url, array('tag_id' => $tags ) );
								}
								
								break;
									
							case 'tag_clear':
									
									$url = Url::removeParams( $url, array('tag_id', 'jump') );
									break;
				
							case 'search':
				
								$this->isInRequest( 'search' );
									
								if(@$aUrl['query_params']['jump'] != 'all')
									$url = Url::removeParams( $url, array('jump') );
								$url = Url::updateParams( $url, array('search' => urlencode($this->request['search']) ) );
				
								break;
				
							case 'favelet':
				
								$url = Url::setParams( $url, array(), array('module') );
								$url = Url::updateParams( $url, array('page' => 'favelet', 'module' => 'bookmarks') );
				
								break;
				
							case 'change_module':
				
								$this->isInRequest('new_module');
				
								$url = Url::setParams( $url, array() );
								$url = Url::updateParams( $url, array('module' => $this->request['new_module'] ) );
									
								break;
				
						}
							
						$_SESSION['url_bookmarks'] = $url;
							
						if(isset($this->request['redirect']) && $this->request['redirect'] == 0)
							die($_SESSION['url_bookmarks']);
						else
							header('Location: '.$_SESSION['url_bookmarks']);
				
						break;
				
				case 'save':				
					$this->isInRequest( array( 'data') );
					foreach($this->_request['data'] as $item) {
						if (!isset($item['id']) ||
							!isset($item['model']) ||
							!isset($item['key']) ||
							!isset($item['value']) ) {
							continue;
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
				
				case 'trash':
						$this->isInRequest( array( 'id', 'model') );
							
						$this->_request['id'] = filter_var($this->_request['id'], FILTER_VALIDATE_INT);
						$this->_request['model'] = Sanitize::FileName($this->_request['model']);	
						require_once("models/".$this->_request['model']."/".$this->_request['model'].".class.php");
						$model = new $this->_request['model'](array('id'=>$this->_request['id']));
					
						die($model->delete());
						break;
						
				case 'delete':
					$this->isInRequest( array( 'id', 'model') );

					$this->_request['id'] = filter_var($this->_request['id'], FILTER_VALIDATE_INT);
					$this->_request['model'] = Sanitize::FileName($this->_request['model']);
						
					require_once("models/".$this->_request['model']."/".$this->_request['model'].".class.php");
					$model = new $this->_request['model'](array('id'=>$this->_request['id']));
						
					die($model->delete());
					break;
						
				case 'emptybin':
						$this->isInRequest( array( 'model') );
							
						$this->_request['model'] = Sanitize::FileName($this->_request['model']);
							
						require_once("models/".$this->_request['model']."/".$this->_request['model'].".class.php");
						$model = new $this->_request['model']();
					
						$model->emptyBin();
						header('Location: '.$_SESSION['url_bookmarks']);
						break;
				
				case 'newfile':
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
					
				case 'update_sorting_order':
					
					$this->isInRequest( array('prev_id', 'atm_id') );
					
					// get a_sort of previous item
					if($this->_request['prev_id'] != 0) {
						$query = "SELECT
								a_sort
							  FROM
								article
							  WHERE
								id = :prev_id";
						$data = DB::getOne($query, array(':prev_id' => $this->_request['prev_id']));
						$a_sort_prev = $data['a_sort'];
						$a_sort_current = ++$a_sort_prev;
					} else {
						$a_sort_current = 0;	
					}
					
					
					// update a_sort of current item
					$query = 'UPDATE 
								article 
							  SET 
								a_sort=:a_sort
					  		  WHERE 
								id=:id';
					$params = array(
						':a_sort' => $a_sort_current, 
						':id' => $this->_request['atm_id']
					);
					DB::execute($query, $params);
					
					
					// update a_sort of all following items
					$query = "SELECT
								*
							  FROM
								article
							  WHERE
								a_sort >= :a_sort_current
							  AND
								id != :id_current
							  ORDER BY
							    a_sort ASC";
					$params = array(
						':a_sort_current' => $a_sort_current,
						':id_current' => $this->_request['atm_id']
					);
					$data = DB::get($query, $params);					
					
					$query = 'UPDATE
								article
							  SET
								a_sort=:a_sort
					  		  WHERE
								id=:id';
					
					foreach($data as $item) {
						
						$params = array(
							':a_sort' => ++$a_sort_current,
							':id' => $item['id']
						);
						DB::execute($query, $params);
						
					}
					die();
					
					break;			
				
			
			} // <!-- end ’switch(@$this->_request['page'])’ -->
		
		} // <!-- end function ’control()’ -->
		
	} // <!-- end class ’ControllerAjax’ -->   
?>
