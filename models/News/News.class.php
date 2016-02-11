<?php

	/**
	*	Copyright 2010-2014 René Michalke.
	*/
    require_once("models/Article/Article.class.php");

	class News extends ModelList {
		
		protected $_name = "News";
		protected $_month = NULL;
		protected $_year = NULL;
		
		public function __construct($request = NULL) {
			$this->_request = $request;

			$this->_tag_id = @$this->_request['tag_id'] ? $this->_request['tag_id'] : NULL;
			$this->_month = @$this->_request['month'] ? $this->_request['month'] : NULL;
			$this->_year = @$this->_request['year'] ? $this->_request['year'] : NULL;
			$this->load();
		}
		
		public function load() {

			// Select Articles by TAG
			if($this->_tag_id != NULL) {

				require_once("models/Tag/Tag.class.php");

				if(!Tag::doesExist($this->_tag_id)) {
					$this->_data['content'] = array();
					return false;
				}

				list($wheres, $this->_params) = $this->getWheres("rel_articles_categories");

				if($wheres == "") {
					$data = array();
				} else {
					$this->_query = "SELECT id FROM article WHERE ".$wheres." ORDER BY a_date DESC";
				}

			// Select Articles by MONTH and YEAR
			} elseif($this->_month != NULL) { 			

				$this->_params = array(
					"month" => $this->_month,
					"year"  => $this->_year
				);

				switch(Config::getOption("db_type")) {
					case "mysql": 
						$wheres = 'DATE_FORMAT(a_date, "%m") = :month AND DATE_FORMAT(a_date, "%Y") = :year';
					break;
				
					case "sqlite": 
						$wheres = 'STRFTIME("%m", a_date) = :month AND STRFTIME("%Y", a_date) = :year';
					break;
				}

				$this->_query = "SELECT id FROM article WHERE ".$wheres." ORDER BY a_date DESC";

			// Select Articles only by YEAR
			} elseif($this->_year != NULL) {

				switch(Config::getOption("db_type")) {
					case "mysql": 
						$wheres = 'DATE_FORMAT(a_date, "%Y") = \''. $this->_year .'\'';
					break;
				
					case "sqlite": 
						$wheres = 'STRFTIME("%Y", a_date) = \''. $this->_year .'\'';
					break;
				}

				$this->_query = "SELECT id FROM article WHERE ".$wheres." ORDER BY a_date DESC";

			// Select all Articles
			} else {

				$this->_query = 'SELECT * FROM article ORDER BY a_date DESC';

			}

			// Get Data From DB
			$data = $this->getDataWithPages(Config::getOption('articles_per_page'));
			
						
			// Create Instances of Articles
			if(!sizeof($data)) {
				$this->_data['content'] = array();
			} else
				foreach($data as $key => $item) {
	                $tmp = new Article(array('id'=>$item['id']));
	                $this->_data['content'][$key] =  $tmp->display();
	            }
		}		
		
	}
?>
