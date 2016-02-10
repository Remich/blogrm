<?php

	/**
	*	Copyright 2010-2014 René Michalke.
	*/
    require_once("models/RSSItem/RSSItem.class.php");

	class RSSFeed extends ModelList {
		
		protected $_name = "RSSFeed";
		
		public function __construct() {
			$this->load();
		}
		
		public function load() {
			
			$this->_query = 'SELECT * FROM article ORDER BY a_date DESC';
			$data = $this->getData();
						
			if(!sizeof($data)) {
				$this->_data['content'] = array();
			} else {
				foreach($data as $key => $item) {
	                $tmp = new RSSItem(array('id'=>$item['id']));
	                $this->_data['content'][$key] =  $tmp->display();
	            }
	        }

	        // Feed Title
	        $this->_data['feed_title'] = Config::getOption("page_title")." – RSS Feed";
		}		
		
	}
?>
