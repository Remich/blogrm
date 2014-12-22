<?php

	/**
	 * @file
	 * Copyright 2010-2014 René Michalke.
 	 */
	
	require_once('interfaces/iDBContentStatic.interface.php');
	
	class Bookmark extends ModelSingle implements iDBContentStatic {
		
		protected $_name = "Bookmark";
		
		/**
		* Constructor
		*
		*/
		public function __construct($array = null) {
			
			if($array == null || sizeof($array) == 0) {
				$this->_data['title'] = "Das ist der Default Bookmark Title";
				$this->_data['url'] = "http://renemichalke.de";
				$this->newEntry();
			}
			if(isset($array['id']))
				$this->load($array['id']);
			
			if(isset($array['data']) 
					&& isset($array['data']['title']) 
					&& isset($array['data']['url'])
			) {
				$this->_data = $array['data'];
				$this->newEntry();
			}
			
		} 
		
		public function newEntry() {			
			$query = 'INSERT INTO
						bookmark_items (uid, title, url, date)
					  VALUES
					    (:uid, :title, :url, NOW())';
			$params = array(
					':uid' => 1,
					':title' => $this->_data['title'],
					':url'	 => $this->_data['url']
			);
			DB::execute($query, $params);
			

			$lastID = DB::lastId('id');
			$this->_data['id'] = $lastID;
			
			require_once('models/TagManager/TagManager.class.php');
			$catman = new TagManager($lastID, "Uncategorized");
			$catman->setTableRelation("rel_bitems_btags");
			$catman->setTableTags("bookmark_tags");
			$catman->generate();
				
			$this->load($lastID);
		}
		
		public function set($array) {
			foreach($array as $key => $item)
				if($key == "content") {
					$this->_data['url'] = $this->extractUrl($item);
					$this->_data['title'] = $this->extractTitle($item);	
				} else
					$this->_data[$key] = preg_replace( "/\r|\n|&nbsp;/", "", trim($item));
		}
		
		private function extractUrl($item) {
			$von = strpos($item, '"');
			$bis = strpos($item, '"', $von+1);
			return preg_replace( "/\r|\n|&nbsp;/", "", trim(substr($item, $von+1, $bis-$von-1)) );
		}
		
		private function extractTitle($item) {
			return preg_replace( "/\r|\n|&nbsp;/", "", trim(strip_tags($item)) );
		}
		
		
		public function load($id) {
			$query = "SELECT *, DATE_FORMAT(date, '%W %M %Y') as date 
					  FROM bookmark_items WHERE id = :id";
			
			$this->_data = DB::getOne($query, array(':id' => $id));
			
			$query = "SELECT id_b FROM rel_bitems_btags WHERE id_a = :id_a";
			$data = DB::get($query, array(':id_a' => $id));
			$cats = array();
			foreach($data as $key => $item) {
				$query = "SELECT name FROM bookmark_tags WHERE id = :id_b";
				$data = DB::getOne($query, array(':id_b' => $item['id_b']));
				$cats[] = $data['name'];				
			}
			if(sizeof($cats) == 0)
				$cats[] = 'Uncategorized';
			
			$this->_data['categories'] = implode(" #", $cats);
						
			if(sizeof($this->_data) == 0)
				$this->set(  array(
					'id' => $id, 
					'title' => "Fehler: Bookmark mit id ".$id." nicht gefunden"
				) );
				
			$options = array('htmlspecialchars', 'utf8_decode', 'stripslashes');
			Sanitize::process_array($this->_data, $options);
		}
		
		public function save() {
			$query = 'UPDATE bookmark_items SET 
						title=:title,
						url=:url
					  WHERE id=:id';
			$params = array(
				':title' => $this->_data['title'],
				':url' => $this->_data['url'],
				':id' => $this->_data['id']);
			DB::execute($query, $params);			

			require_once('models/TagManager/TagManager.class.php');
			$catman = new TagManager($this->_data['id'], $this->_data['categories']);
			$catman->setTableRelation("rel_bitems_btags");
			$catman->setTableTags("bookmark_tags");
			$catman->generate();
		}
		
		public function trash() {			
			$query = 'UPDATE 
						bookmark_items
					  SET
						trashed = :trashed
					WHERE id = :id';
			$params = array(':trashed' => !$this->_data['trashed'], ':id' =>  $this->_data['id']);
			DB::execute($query, $params);

			require_once('models/TagManager/TagManager.class.php');
			$catman = new TagManager($this->_data['id'], $this->_data['categories']);
			$catman->setTableRelation("rel_bitems_btags");
			$catman->setTableTags("bookmark_tags");
			$catman->generate();		
			$str = "Bookmark erfolgreich ".(!$this->_data['trashed']?"in Mülleimer verschoben":"wiederhergestellt");
			die($str);
		}
		
		public function delete($id) {
						
			$query = 'DELETE FROM
						bookmark_items
					  WHERE id = :id';
			DB::execute($query, array(':id' => $id));

			require_once('models/TagManager/TagManager.class.php');
			$catman = new TagManager($id, $this->_data['categories']);
			$catman->setTableRelation("rel_bitems_btags");
			$catman->setTableTags("bookmark_tags");
			$catman->generate();
			return true;
		}
		

		public function emptyBin() {
			$query = "SELECT * FROM bookmark_items WHERE trashed = 1";
			$data = DB::get($query);
			
			foreach($data as $key => $item) {
				$tmp = new Bookmark($item['id']);
				$tmp->delete($item['id']);	
				echo "deleting";			
			}
			
			$query = 'DELETE FROM
						bookmark_items
					  WHERE id = :id';
			$params = array(':id' => $this->_data['id']);
			DB::execute($query, $params);
			return true;
		}
		
		public function go() {	
			$this->incHits();	
			header('Location: '.$this->_data['url']);			
		}
		
		private function incHits() {
			
			$query = 'SELECT hits FROM bookmark_items WHERE id = :id LIMIT 1';
			$params = array(
					':id' => $this->_data['id']
			);
			$data = DB::getOne($query, $params);
			

			if(!$data) die('Software Error: query returned no results.');
			
			$data['hits']++;
			
			$query = 'UPDATE bookmark_items SET hits = :hits, last_hit = NOW(), thumbnail_update = 1 WHERE id = :id';
			$params = array(
					':id' => $this->_data['id'],
					':hits' => $data['hits']
			);
			DB::execute($query, $params);
		}

	} // <!-- end class ’Controller’ -->
?>
