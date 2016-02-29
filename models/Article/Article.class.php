<?php

	/**
	 * @file
	 * Copyright 2010-2014 René Michalke.
 	 */
	
	require_once('interfaces/iDBContentStatic.interface.php');
    require_once("models/ModelSingle/ModelSingle.class.php");
	
	class Article extends ModelSingle implements iDBContentStatic {
		
		protected $_name = "Article";
		protected $_table = "articles";
		
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

				if (trim($array['id']) === "" ||
					!is_numeric($array['id'])) {
					die("ERROR: Id is empty or not numeric! (Article::__construct())");
				}

				$this->_id = $array['id'];

				if ( !$this->doesExist() ) {
					die("ERROR: Article " . $this->_id . " not found! (Article::__construct())");
				}

				$this->loadEntry();

			// Data has been supplied.
			// Validate data and load current instance with supplied data
			} elseif( isset($array['data']) ) {

				if (!isset($array['data']['title']) ||
					!isset($array['data']['content']) ||
					!isset($array['data']['tags'])) {
					die("ERROR: Missing data! (Article::__construct())");
				} else {

					// Check id	
					if (isset($array['data']['id'])) {
						if (trim($array['data']['id']) === "" ||
							!is_numeric($array['data']['id'])) {
							die("ERROR: Id is empty or not numeric! (Article::__construct())");
							}
						$this->_id = $array['data']['id'];

						// Check if article with supplied id exists
						if ( !$this->doesExist() ) {
							die("ERROR: Article " . $this->_id . " not found! (Article::__construct())");
						}
					}


					// Check title
					if (trim($array['data']['title']) === "") {
						die("ERROR: Title is empty! (Article::__construct())");
					}
					$this->_data['title'] = $array['data']['title'];

					// Check content
					if (trim($array['data']['content']) === "") {
						die("ERROR: Content is empty! (Article::__construct())");
					}
					$this->_data['content'] = $array['data']['content'];

					// Check tags
					if (trim($array['data']['tags']) === "") {
						die("ERROR: Tags is empty! (Article::__construct())");
					}
					$this->_data['tags'] = $array['data']['tags'];

				}

			}

		} 

		
		
		public function loadEntry() {

			switch(Config::getOption("db_type")) {
				case "mysql":
					$query = "SELECT *, DATE_FORMAT(a_date, '%D %M %Y – %H:%i') as a_date, (SELECT COUNT(*) as no_of_comments FROM comments WHERE a_id = :id) as no_of_comments FROM articles WHERE id = :id";
					break;

				case "sqlite":
					$query = "SELECT *, strftime('%d.%m.%Y – %H:%M', a_date) AS a_date, (SELECT COUNT(*) as no_of_comments FROM comments WHERE a_id = :id) as no_of_comments FROM articles WHERE id = :id";
					break;
			}
			$this->_data = DB::getOne($query, array(':id' => $this->_id));

			require_once('models/TagManager/TagManager.class.php');
			$tagmanager = new TagManager($this->_id);
			$tagmanager->setTableRelation("rel_articles_tags");
			$tagmanager->setTableTags("tags");
			$this->_data['tags'] = $tagmanager->getTags();
		}

		public function loadComments() {
 			require_once("models/ListOfComments/ListOfComments.class.php");
 			$comments = new ListOfComments($this->_id);
 			$this->_data['comments'] = $comments->display();
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
			// dump($catman);
			// die();
		}

		public function newEntry() {
			$query = "INSERT INTO articles 
						(title, a_date, content) 
					  VALUES 
						(:title, :a_date, :content)";
			$params = array(
				':title' => $this->_data['title'],
				':content' => $this->_data['content'],
				':a_date' => $this->_data['a_date']
			);
			DB::execute($query, $params);

			$this->_id = DB::lastId();
		}

		public function updateEntry() {

			$query = 'UPDATE articles SET 
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
			$query = 'DELETE FROM articles
						WHERE id = :id';
			$params = array('id' => $this->_id);
			DB::execute($query, $params);
			die("#t");
		}

	} // <!-- end class "Article" -->
?>
