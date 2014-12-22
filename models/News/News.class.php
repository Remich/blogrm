<?php

	/**
	*	Copyright 2010-2014 René Michalke.
	*/
    require_once("models/Article/Article.class.php");

	class News extends ModelList {
		
		protected $_name = "News";
		
		public function __construct($tag_id = null, $request = null) {
			$this->_request = $request;
			$this->_tag_id = $tag_id;
			$this->load();
		}
		
		public function load() {
			if($this->_tag_id == null) {
				$this->_query = 'SELECT * FROM article ORDER BY a_sort DESC';
				$data = $this->getDataWithPages(Config::getOption('articles_per_page'));
			} else {			
				// TODO: Escape
				$wheres = $this->getWheres("rel_articles_categories");
				if($wheres == "") {
					$data = array();
				} else {
					$this->_query = "SELECT id FROM article WHERE ".$wheres." ORDER BY a_date DESC";
					$data = $this->getDataWithPages(Config::getOption('articles_per_page'));
				}
			}
			
						
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
