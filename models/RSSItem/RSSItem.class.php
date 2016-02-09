<?php

	/**
	 * @file
	 * Copyright 2010-2014 René Michalke.
 	 */
	
	require_once('interfaces/iDBContentStatic.interface.php');
	
	class RSSItem extends ModelSingle implements iDBContentStatic {
		
		protected $_name = "RSSItem";
		
		public function __construct($array = null) {
			
			if($array == null || sizeof($array) == 0) {
				$this->_data['id'] = "Das ist ein leerer RSS Feed";
			}
			if(isset($array['id']))
				$this->load($array['id']);
					
		} 
		
		public function load($id) {
			$query = "SELECT *, DATE_FORMAT(a_date, '%a, %d %b %Y %T') as a_date_rss
					  FROM article WHERE id = :id";
			
			$this->_data = DB::getOne($query, array(':id' => $id));
			
			$query = "SELECT id_b FROM rel_articles_categories WHERE id_a = :id_a";
			$data = DB::get($query, array(':id_a' => $id));
			$cats = array();
			foreach($data as $key => $item) {
				$query = "SELECT name FROM categories WHERE id = :id_b";
				$data = DB::getOne($query, array(':id_b' => $item['id_b']));
				$cats[] = $data['name'];				
			}
			if(sizeof($cats) == 0)
				$cats[] = 'Uncategorized';
			
			$this->_data['categories'] = implode(", ", $cats);

			$this->_data['a_date_rss'] .= " " . date('T');
						
			if(sizeof($this->_data) == 0)
				$this->set(  array(
					'id' => $id, 
					'title' => "Fehler: Artikel mit id ".$id." nicht gefunden",
					'content' => "Fehler: Artikel mit id ".$id." nicht gefunden"
				) );
				
			$options = array('htmlspecialchars', 'utf8_decode', 'stripslashes');
			Sanitize::process_array($this->_data, $options);
		}

		public function save() {}

	} // <!-- end class ’Controller’ -->
?>
