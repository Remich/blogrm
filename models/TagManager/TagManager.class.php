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
		private $_tags = array();
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
		
		public function __construct($a_id = NULL) {
			if($a_id === NULL) {
				die("ERROR: No id_a for TagManager __construct supplied!");
			} elseif (!is_int($a_id)) {
				die("ERROR: id_a is not of type int.");
			} else {
				$this->_a_id = $a_id;
			}
		}
		
		public function setTags($tags) {
			$this->_tags = strip_tags($tags);
			$this->_tags = str_replace("&nbsp;", "", $this->_tags);
			$this->_tags = explode('#', $this->_tags);
			Sanitize::process_array($this->_tags, array('trim'));
			foreach($this->_tags as $key => $item) {
				if(trim($item) == "")
					unset($this->_tags[$key]);
			}
		}

		public function getTags() {

			// TODO: make nicer by JOIN
			$query = "SELECT id_b FROM ".$this->_table_relation." WHERE id_a = :id_a";
			$data = DB::get($query, array(':id_a' => $this->_a_id));
			$cats = array();
			foreach($data as $key => $item) {
				$query = "SELECT id, name FROM tags WHERE id = :id_b";
				$data = DB::getOne($query, array(':id_b' => $item['id_b']));
				$cats[] = "<a href=\"index.php?page=tag&tag_id=" . $data['id'] . "\">#" . $data['name'] . "</a>";			
			}
			if(sizeof($cats) == 0)
				$cats[] = 'Uncategorized';

			return implode(" ", $cats);

		}
		
		public function updateTags() {
			$this->createInstancesOfTags();
			$this->deleteUnusedTags();
			$this->updateRelations();			
		}

		private function createInstancesOfTags() {
			require_once('models/Tag/Tag.class.php');
			foreach($this->_tags as $key=>$c_name) {
				$this->_tags[$key] = new Tag(array('name'=>$c_name));
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
