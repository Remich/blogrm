<?php

	/**
	 * @file
	 * Copyright 2010-2014 René Michalke.
 	 */
	
	require_once('interfaces/iDBContentStatic.interface.php');
	
	class Article extends ModelSingle implements iDBContentStatic {
		
		protected $_name = "Article";
		
		public function __construct($array = null) {

			$this->_data['title'] = "Das ist der Default Article Title";
			$this->_data['url'] = "Lorem Ipsum Articlum";
			
			if ($array == null || sizeof($array) == 0 || ! $this->IDExists($array['id'])) {
				$this->newEntry();
			} elseif (isset($array['id']) &&
				$this->IDExists($array['id'])) {
				$this->load($array['id']);
			} elseif (isset($array['data'])) {
				$this->_data = $array['data'];
				$this->newEntry();
			}
			
		} 

		
		// TODO: implement in ParentClass
		public function IDExists($id) {
			$query = "SELECT COUNT(id) as quantity
						FROM article
						WHERE id = :id LIMIT 1";
			$params = array(':id' => $id);
			$data = DB::getOne($query, $params);

			if ($data['quantity'] === "0") {
				return false;
			} else {
				return true;
			}
		}
		
		public function newEntry() {

			switch(Config::getOption("db_type")) {

				case "mysql":
					$query = "INSERT INTO article 
								(title, a_date, content) 
							  VALUES 
								(:title, NOW(), :content)";
					break;

				case "sqlite":
					$query = "INSERT INTO article 
								(title, a_date, content) 
							  VALUES 
								(:title, datetime('now'), :content)";
					break;
			}

			$params = array(
					':title' => $this->_data['title'],
					':content' => (isset($this->_data['content']) ? $this->_data['content'] : 'Das ist der Default-Content')
			);
			DB::execute($query, $params);
			$lastID = DB::lastId('id');
			
			require_once('models/TagManager/TagManager.class.php');
			$catman = new TagManager($lastID, (isset($this->_data['tags']) ? $this->_data['tags'] : "Uncategorized"));
			$catman->setTableRelation("rel_articles_categories");
			$catman->setTableTags("categories");
			$catman->generate();
			
			$this->load($lastID);
		}
		
		public function load($id) {

			switch(Config::getOption("db_type")) {
				case "mysql":
					$query = "SELECT *, DATE_FORMAT(a_date, '%D %M %Y – %H:%i') as a_date 
							  FROM article WHERE id = :id";
					break;

				case "sqlite":
					$query = "SELECT *, strftime('%d.%m.%Y – %H:%M', a_date) as a_date 
							  FROM article WHERE id = :id";
					break;
			}
			
			$this->_data = DB::getOne($query, array(':id' => $id));

			$query = "SELECT id_b FROM rel_articles_categories WHERE id_a = :id_a";
			$data = DB::get($query, array(':id_a' => $id));
			$cats = array();
			foreach($data as $key => $item) {
				$query = "SELECT id, name FROM categories WHERE id = :id_b";
				$data = DB::getOne($query, array(':id_b' => $item['id_b']));
				$cats[] = "<a href=\"index.php?page=tag&tag_id=" . $data['id'] . "\">#" . $data['name'] . "</a>";			
			}
			if(sizeof($cats) == 0)
				$cats[] = 'Uncategorized';
			
			$this->_data['categories'] = implode(" ", $cats);

 			require_once("models/Comment/Comment.class.php");
			$query = "SELECT * FROM comment WHERE a_id = :a_id";
			$data = DB::get($query, array(':a_id' => $id));
			foreach($data as $key => $item) {
				$tmp = new Comment(array("id"=>$item['id']));
				$data[$key] = $tmp->display();
			}
			$this->_data['comments'] = $data;
		
						
			if(sizeof($this->_data) == 0)
				$this->set(  array(
					'id' => $id, 
					'title' => "Fehler: Artikel mit id ".$id." nicht gefunden",
					'content' => "Fehler: Artikel mit id ".$id." nicht gefunden"
				) );
				
			// $options = array('stripslashes');
			// Sanitize::process_array($this->_data, $options);
		}
		
		public function save() {
			$query = 'UPDATE  article SET 
						title=:t_value,
						content=:c_value
					  WHERE id=:id';
			$params = array(
				':t_value' => $this->_data['title'], 
				':c_value' => $this->_data['content'], 
				':id' => $this->_data['id']);
			DB::execute($query, $params);
			
			require_once('models/TagManager/TagManager.class.php');
			$catman = new TagManager($this->_data['id'], $this->_data['categories']);
			$catman->setTableRelation("rel_articles_categories");
			$catman->setTableTags("categories");
			$catman->generate();
		}

		public function delete() {
			// remove category-relation
			require_once('models/TagManager/TagManager.class.php');
			$catman = new TagManager($this->_data['id'], "");
			$catman->setTableRelation("rel_articles_categories");
			$catman->setTableTags("categories");
			$catman->generate();

			// delete item
			$query = 'DELETE FROM article
						WHERE id = :id';
			$params = array('id' => $this->_data['id']);
			DB::execute($query, $params);
			return "true";
		}

	} // <!-- end class ’Controller’ -->
?>
