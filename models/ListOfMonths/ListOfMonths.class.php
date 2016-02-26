<?php

	/**
	*	Copyright 2010-2014 René Michalke.
	*/
    require_once("models/Article/Article.class.php");

	class ListOfMonths extends ModelList {
		
		protected $_name = "ListOfMonths";
		
		public function __construct() {
			$this->load();
		}
		
		public function load() {

			switch(Config::getOption("db_type")) {
				case "mysql":
					$this->_query = 'SELECT DISTINCT DATE_FORMAT(a_date, "%m:%Y") as month FROM article ORDER BY a_date ASC';
				break;

				case "sqlite":
					$this->_query = 'SELECT DISTINCT STRFTIME("%m:%Y", a_date) as month FROM article ORDER BY a_date ASC';
				break;
			}
			$data = $this->getData();

			$months_numeric = array(
				"01",
				"02",
				"03",
				"04",
				"05",
				"06",
				"07",
				"08",
				"09",
				"10",
				"11",
				"12"
			);

			$months = array(
				"January",
				"February",
				"March",
				"April",
				"May",
				"June",
				"July",
				"August",
				"September",
				"October",
				"November",
				"December"
			);

			Misc::dump($data);

			foreach($data as $key => $item) {
				$tmp = explode(":", $item['month']);
				$data[$key]['month_numeric'] = $tmp[0];
				$tmp[0] = str_replace($months_numeric, $months, $tmp[0]);
				$data[$key]['month'] = $tmp[0];
				$data[$key]['year'] = $tmp[1];
			}

			if(!sizeof($data)) {
				$this->_data['content'] = array();
			} else
				$this->_data['content'] = $data;
		}		
		
	}
?>
