<?php

	/**
	*	Copyright 2010-2014 René Michlke
	*
	*	This file is part of RM Internet Suite.
	*/
	

	/**
	* class TagManager
	*
	*/
	class TagManager {
		
		private $_a_id;
		private $_tags;
		private $_tags_new;
		private $_table_relation = null;
		private $_table_tags = null;
		
		public function setTableRelation($table = null) {
			if($table == null)
				die('Error: Nullreference Table Relation');
				
			$this->_table_relation = $table;
		}
		public function setTableTags($table = null) {
			if($table == null)
				die('Error: Nullrefrence Table Relation');
		
			$this->_table_tags = $table;
		}
		
		public function __construct($a_id, $tags) {
			$this->_a_id = $a_id;
			$this->receiveStringOfTags($tags);
		}
		
		public function receiveStringOfTags($tags) {
			$this->_tags = strip_tags($tags);
			$this->_tags = explode('#', $this->_tags);
			$this->_tags = Sanitize::trim($this->_tags);
			foreach($this->_tags as $key => $item)
				if($item == "")
					unset($this->_tags[$key]);
		}
		
		public function generate() {
			$this->createInstancesOfTags();
			$this->deleteUnusedTags();
			$this->updateRelations();			
		}

		private function createInstancesOfTags() {
			require_once('models/Tag/Tag.class.php');
			foreach($this->_tags as $key=>$c_name) {
				$this->_tags[$key] = new Tag(array('table'=>$this->_table_tags, 'name'=>$c_name));
				//unset($this->_tags[$key]);
			}
		}
		
		
		private function deleteUnusedTags() {
			// get all tags used by this article and 
			// check wether a tag is still used by this article

			//require_once('models/Model/Article.class.php');
			$params = array(
				'table' => "article",
				'id' => $this->_a_id
			);
			$article = new Model($params);
			foreach($article->getTags($this->_table_relation) as $item) {
				
				$tags = array();
				foreach($this->_tags as $item2)
					$tags[] = $item2->getId($this->_table_tags);
				
				if(!in_array($item['id_b'], $tags)) {

					// tag is not being used anymore by that article,
					// so we delete it from relations table
					require_once('models/Relation/Relation.class.php');
					$rel = new Relation($this->_table_relation, $this->_a_id, $item['id_b']);
					$rel->delete();
					
					// check if the tag is being used by any other article		
					require_once('models/Tag/Tag.class.php');
					$tag = new Tag(array('table'=>$this->_table_tags, 'id'=>$item['id_b']));
					if(sizeof($tag->getRelations($this->_table_relation)) == 0) {
						// NO, the tag can also be deleted from the tag table
						$tag->delete();
					}
				}				
			}			
		}
		
		private function updateRelations() {
			require_once('models/Relation/Relation.class.php');
			foreach($this->_tags as $id_b)
				$rel_tmp = new Relation($this->_table_relation, $this->_a_id, $id_b->getId());
		}				
		
	}
	
?>
