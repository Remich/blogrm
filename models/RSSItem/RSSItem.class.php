<?php

	/**
	 * @file
	 * Copyright 2010-2014 René Michalke.
 	 */
	
	require_once('interfaces/iDBContentStatic.interface.php');
	
	class RSSItem extends ModelSingle implements iDBContentStatic {
		
		protected $_name = "RSSItem";
		protected $_table = "article";	
					
		
		public function __construct($id = NULL) {

			if($id === NULL) {
				die("ERROR: No id for RSSItem supplied!");
			} elseif (!is_int($id)) {
				die("ERROR: Supplied id is not of type int!");
			} else {
				$this->_id = $id;
			}

			if ( $this->doesExist() ) {
				$this->loadEntry();
			} else {
				die("RSSItem " . $this->_id . " not found!");
			}

		} 
		
		public function loadEntry() {

			switch(Config::getOption("db_type")) {
				case "mysql": 
					$query = "SELECT *, DATE_FORMAT(a_date, '%a, %d %b %Y %T') as a_date_rss
							  FROM article WHERE id = :id";
				break;
			
				case "sqlite": 
					$query = "SELECT *, 
									case cast (strftime('%w', a_date) as integer)
when 0 then 'Sun'
when 1 then 'Mon'
when 2 then 'Tue'
when 3 then 'Wed'
when 4 then 'Thu'
when 5 then 'Fri'
else 'Sat' end as weekday, 
  									case cast (strftime('%m', a_date) as integer)
when 1 then 'Jan'
when 2 then 'Feb'
when 3 then 'Mar'
when 4 then 'Apr'
when 5 then 'May'
when 6 then 'Jun'
when 7 then 'Jul'
when 8 then 'Aug'
when 9 then 'Sep'
when 10 then 'Oct'
when 11 then 'Nov'
else 'Dec' end as month,
  		STRFTIME('%d', a_date) as day_of_month,
  		STRFTIME('%Y', a_date) as year,
  		TIME(a_date) as hhmmss
							  FROM article WHERE id = :id";
				break;
			}
			
			$this->_data = DB::getOne($query, array(':id' => $this->_id));

			if(Config::getOption("db_type") === "sqlite") {
				$this->_data['a_date_rss'] = $this->_data['weekday'].', '.$this->_data['day_of_month'].' '.$this->_data['month'].' '.$this->_data['year'].' '.$this->_data['hhmmss'];
			}

			$query = "SELECT id_b FROM rel_articles_tags WHERE id_a = :id_a";
			$data = DB::get($query, array(':id_a' => $this->_id));
			$cats = array();
			foreach($data as $key => $item) {
				$query = "SELECT name FROM tags WHERE id = :id_b";
				$data = DB::getOne($query, array(':id_b' => $item['id_b']));
				$cats[] = $data['name'];				
			}
			if(sizeof($cats) == 0)
				$cats[] = 'Uncategorized';
			
			$this->_data['tags'] = implode(", ", $cats);

			$this->_data['a_date_rss'] .= " " . date('T');
						
			if(sizeof($this->_data) == 0)
				$this->set(  array(
					'id' => $this->_id, 
					'title' => "Fehler: Artikel mit id ".$this->_id." nicht gefunden",
					'content' => "Fehler: Artikel mit id ".$this->_id." nicht gefunden"
				) );
				
			$options = array('htmlspecialchars', 'utf8_decode', 'stripslashes');
			Sanitize::process_array($this->_data, $options);
		}

		public function saveEntry() {}

	} // <!-- end class ’Controller’ -->
?>
