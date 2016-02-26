<?php

	/**
	 * @file
	 * Copyright 2010-2014 René Michalke.
 	 */
	
	require_once('interfaces/iDBContentStatic.interface.php');
	
	class Article extends ModelSingle implements iDBContentStatic {
		
		protected $_name = "Article";
		protected $_table = "article";
		
		public function __construct($array = null) {

			// Load Default Values
			$this->_data['title'] = "Default Article Title";
			$this->_data['content'] = "Default Article Content";
			$this->_data['tags'] = "Uncategorized";

			$this->_id = -1;

			switch(Config::getOption("db_type")) {
				case "mysql": 
					$query = "SELECT NOW() as a_date";
				break;
			
				case "sqlite": 
					$query = "SELECT DATETIME('NOW') as a_date";
				break;
			}
			$this->_data['a_date'] = DB::getOne($query)['a_date'];

			// A single id has been supplied.
			// Check wether article with that id does exist and load it
			if ( isset($array['id']) ) {

				$this->_id = $array['id'];

				if ( $this->doesExist() ) {
					$this->loadEntry();
				} else {
					die("Article " . $this->_id . " not found!");
				}

			// Data has been supplied.
			// Validate data and load current instance with supplied data
			} elseif( isset($array['data']) ) {

				if (!isset($array['data']['title']) ||
					!isset($array['data']['content']) ||
					!isset($array['data']['tags'])) {
					die("Not all Article data supplied");
				} else {

					// Check id	
					if (isset($array['data']['id'])) {
						if (trim($array['data']['id']) === "" ||
							!is_numeric($array['data']['id'])) {
							die("Supplied id for Article is empty or not numeric");
							} else {
							$this->_id = $array['data']['id'];
						}
					}

					// Check title
					if (trim($array['data']['title']) === "") {
						die("Supplied title for Article is empty");
					} else {
						$this->_data['title'] = $array['data']['title'];
					}

					// Check content
					if (trim($array['data']['content']) === "") {
						die("Supplied content for Article is empty");
					} else {
						$this->_data['content'] = $array['data']['content'];
					}

					// Check tags
					if (trim($array['data']['tags']) === "") {
						die("Supplied tags for Article is empty");
					} else {
						$this->_data['tags'] = $array['data']['tags'];
					}

				}

			}
			
		} 

		
		
		public function loadEntry() {

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
			$this->_data = DB::getOne($query, array(':id' => $this->_id));

			require_once('models/TagManager/TagManager.class.php');
			$tagmanager = new TagManager((int)$this->_id);
			$tagmanager->setTableRelation("rel_articles_tags");
			$tagmanager->setTableTags("tags");
			$this->_data['tags'] = $tagmanager->getTags();
			

 		// 	require_once("models/Comment/Comment.class.php");
			// $query = "SELECT * FROM comment WHERE a_id = :a_id";
			// $data = DB::get($query, array(':a_id' => $this->_id));
			// foreach($data as $key => $item) {
			// 	$tmp = new Comment(array("id"=>$item['id']));
			// 	$data[$key] = $tmp->display();
			// }
			// $this->_data['comments'] = $data;
		
						
			if(sizeof($this->_data) == 0)
				$this->set(  array(
					'id' => $this->_id, 
					'title' => "Fehler: Artikel mit id ".$this->_id." nicht gefunden",
					'content' => "Fehler: Artikel mit id ".$this->_id." nicht gefunden"
				) );
				
			// $options = array('stripslashes');
			// Sanitize::process_array($this->_data, $options);
		}
		

		public function saveEntry() {
			if ($this->_id === -1) {
				$this->newEntry();
			} else {
				$this->updateEntry();
			}

			require_once('models/TagManager/TagManager.class.php');
			$catman = new TagManager((int)$this->_id);
			$catman->setTags($this->_data['tags']);
			$catman->setTableRelation("rel_articles_tags");
			$catman->setTableTags("tags");
			$catman->updateTags();
		}

		public function newEntry() {
			switch(Config::getOption("db_type")) {
				case "mysql":
					$query = "INSERT INTO article 
								(title, a_date, content) 
							  VALUES 
								(:title, :a_date, :content)";
					break;

				case "sqlite":
					$query = "INSERT INTO article 
								(title, a_date, content) 
							  VALUES 
								(:title, :a_date, :content)";
					break;
			}

			$params = array(
				':title' => $this->_data['title'],
				':content' => $this->_data['content'],
				':a_date' => $this->_data['a_date']
			);
			DB::execute($query, $params);

			$this->_id = DB::lastId('id');
		}

		public function updateEntry() {

			$query = 'UPDATE article SET 
						title=:t_value,
						content=:c_value
					  WHERE id=:id';
			$params = array(
				':t_value' => $this->_data['title'], 
				':c_value' => $this->_data['content'], 
				':id' => $this->_id);
			DB::execute($query, $params);

		}


		public function deleteEntry() {
			// remove category-relation
			require_once('models/TagManager/TagManager.class.php');
			$catman = new TagManager($this->_id);
			$catman->setTableRelation("rel_articles_tags");
			$catman->setTableTags("tags");
			$catman->updateTags();

			// delete item
			$query = 'DELETE FROM article
						WHERE id = :id';
			$params = array('id' => $this->_id);
			DB::execute($query, $params);
			die("#t");
		}

	} // <!-- end class "Article" -->
?>
