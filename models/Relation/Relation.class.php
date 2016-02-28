<?php

	/**
	 * @file
	 * Copyright 2010-2014 René Michalke.
 	 */
	
	
	class Relation {
		
		protected $_id;
		protected $_name = "Relation";
		protected $_table = NULL;
		protected $_id_a = NULL;
		protected $_id_b = NULL;
		
		function __construct($table = NULL, $id_a = NULL, $id_b = NULL) {

			if ($table === NULL) {
				die('ERROR: Missing table-name! (Relation::__construct()');
			}
			if (trim($table) === "") {
				die('ERROR: Table-name is empty! (Relation::__construct()');
			}
			if (!doesTableExist($table)) {
				die('ERROR: Table '.$table.' does not exist! (Relation::__construct()');
			}

			$this->_table = $table;

			if ($id_a === NULL) {
				die('ERROR: Missing id_a! (Relation::__construct()');
			} 
			if (trim($id_a) === "") {
				die('ERROR: id_a is empty! (Relation::__construct()');
			}
			if (!is_numeric($id_a)) {
				die('ERROR: id_a is not numeric! (Relation::__construct()');
			}
			$this->_id_a = $id_a;

			if ($id_b === NULL) {
				die('ERROR: Missing id_b! (Relation::__construct()');
			} 
			if (trim($id_b) === "") {
				die('ERROR: id_b is empty! (Relation::__construct()');
			}
			if (!is_numeric($id_b)) {
				die('ERROR: id_b is not numeric! (Relation::__construct()');
			}
			$this->_id_b = $id_b;			

			$this->load();
		} 

		public function load() {
			$query = 'SELECT * FROM '.$this->_table.' WHERE id_a = :id_a AND id_b = :id_b';
			$this->_data = DB::getOne($query, array(':id_a' => $this->_id_a, ':id_b' => $this->_id_b));
			
			if(sizeof($this->_data)===0)
				$this->newEntry(); 
		}
		
		public function newEntry() {
			$query = 'INSERT INTO 
						'.$this->_table.' (id_a, id_b) 
					  VALUES
						(:id_a, :id_b)';
			$params = array(
				':id_a'=>$this->_id_a,
				':id_b'=>$this->_id_b
			);
			DB::execute($query, $params);
		}
		
		public function delete() {
			$query = 'DELETE FROM '.$this->_table.' WHERE id_a = :id_a AND id_b = :id_b';
			$params = array(':id_a'=>$this->_id_a, ':id_b'=>$this->_id_b);
			DB::execute($query, $params);
		}

	} // <!-- end class ’Controller’ -->
?>
