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
					
				// TODO: remove
				case 'update_sorting_order':
					$bouncer = new Auth();
					
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
