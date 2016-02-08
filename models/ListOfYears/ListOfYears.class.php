<?php

	/**
	*	Copyright 2010-2014 René Michalke.
	*/
    require_once("models/Article/Article.class.php");

	class ListOfYears extends ModelList {
		
		protected $_name = "ListOfYears";
		
		public function __construct($tag_id = null, $request = null) {
			$this->_request = $request;
			$this->_tag_id = $tag_id;
			$this->load();
		}
		
		public function load() {

			switch(Config::getOption("db_type")) {
				case "mysql":
					$this->_query = 'SELECT DISTINCT DATE_FORMAT(a_date, "%Y") as year FROM article ORDER BY a_date ASC';
				break;

				case "sqlite":
					$this->_query = 'SELECT DISTINCT STRFTIME("%Y", a_date) as year FROM article ORDER BY a_date ASC';
				break;
			}
			$data = $this->getData();

			if(!sizeof($data)) {
				$this->_data['content'] = array();
			} else
				$this->_data['content'] = $data;
		}		
		
	}
?>
