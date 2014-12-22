<?php

	/**
	*	Copyright 2010-2014 René Michalke.
	*/
    require_once("models/Article/Article.class.php");

	class ListOfContents extends ModelList {
		
		protected $_name = "ListOfContents";
		
		/**
		* Constructor
		*
		*/
		public function __construct($table, $tag_id = null) {
		
			$this->_table = $table;
			$this->_tag_id = $tag_id;
			$this->load();
			
		} // <!-- end function ’__construct()’ -->
		
		public function load() {
			
			if($this->_tag_id == null) {
				$query = "SELECT * FROM ".$this->_table." ORDER BY a_sort ASC";		
				$this->_data = DB::get($query);
			} else {	
				$wheres = $this->getWheres("rel_articles_categories");
				if($wheres === "") {
					$this->_data = array();
				} else {
					$query = "SELECT * FROM ".$this->_table." WHERE ".$wheres." ORDER BY a_sort ASC";
					$this->_data = DB::get($query);				
				}
			}			
			
			//formerly known as make_save_str_out
			$options = array('stripslashes');
			Sanitize::process_array($this->_data, $options);
		}		
		
	} // <!-- end class ’Controller’ -->   
?>
