<?php

	/**
	 * @file
	 * Copyright 2010-2014 René Michalke.
 	 */
	
	
	class Tag extends ModelSingle {
		
		protected $_name = "Tag";
		protected $_table = "tags";
		
		/**
		* Constructor
		*
		*/
		function __construct($array = NULL) {

			if ($array === NULL) {

				$this->_data['name'] = "Default Tag Name";

			} elseif ( isset($array['id']) ) {

				if (trim($array['id']) === "" ||
					!is_numeric($array['id'])) {
					die("ERROR: ID is empty or not numeric! Tag::__construct()");
				}

				$this->_id = $array['id'];

				if ( !$this->doesExist() ) {
					die("ERROR: Tag " . $this->_id . " not found! Tag::__construct()");
				}
				
				$this->load();

			} elseif( isset($array['name']) ) {

				if ( $this->doesExistByName( $array['name']) ) {
					$this->load();
				} else {
					$this->_data['name'] = $array['name'];
					$this->newEntry();
				}

			}
			
		}

		private function doesExistByName( $str ) {
			$query = 'SELECT id FROM '.$this->_table.' WHERE name = :name';		
			$this->_data = DB::getOne($query, array(':name' => $str));
			if (sizeof($this->_data) === 0) {
				return false;
			} else {
				$this->_id = $this->_data['id'];
				return true;
			}
		}

		private function newEntry() {
			$query = 'INSERT INTO '.$this->_table.'
						 (uid, name)
				      VALUES
				          (:uid, :name)';
			$params = array(
				':name' => (isset($this->_data['name'])?$this->_data['name']:"Default Tagname"),
				':uid' => 1
			);
			DB::execute($query, $params);
			$this->_id = DB::lastId('id');
			$this->load();			
		}
		public function setTable($table) {
			$this->_table = $table;
		}
		
		public function getName() {
			return $this->_data['name'];
		}
		public function getId() {
			return $this->_data['id'];
		}
		
		public function getRelations($table) {
			$query = 'SELECT * FROM '.$table.' WHERE id_b = :id_b';
			return  DB::get($query, array(':id_b' => $this->_id));
		}
		
		public function delete() {
			$query = 'DELETE FROM '.$this->_table.' WHERE id = :id';
			$params = array(':id'=>$this->_id);
			DB::execute($query, $params);
		}
		
		public function save() {
			$query = 'UPDATE '.$this->_table.' SET 
						name=:n_value
					  WHERE id=:id';
			$params = array(
				':n_value' => $this->_data['name'],
				':id' => $this->_data['id']);
			DB::execute($query, $params);
		}
		
		public function load() {
			$query = 'SELECT *
					  FROM '.$this->_table.'
					  WHERE id = :id';
				
			$this->_data = DB::getOne($query, array(':id' => $this->_id));
	
			if(sizeof($this->_data) == 0)
				$this->set(  array(
						'id' => $this->_id,
						'title' => "Fehler: Tag mit id ".$this->_id." nicht gefunden"
				) );
		
			$options = array('htmlspecialchars', 'utf8_decode', 'stripslashes');
			Sanitize::process_array($this->_data, $options);
		}

	} // <!-- end class ’Controller’ -->
?>
