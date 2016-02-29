<?php

	/**
	 * @file
	 * Copyright 2010-2014 René Michalke.
 	 */
	
	require_once('interfaces/iDBContentStatic.interface.php');
    require_once("models/ModelSingle/ModelSingle.class.php");
	
	class Comment extends ModelSingle implements iDBContentStatic {
		
		protected $_name = "Comment";
		protected $_table = "comments";
		
		public function __construct($array = null) {

			// Load Default Values
			$this->_data['author'] = "Seth Brundle";
			$this->_data['mail'] = "seth@nomail.com";
			$this->_data['www'] = "brundlefly.com";
			$this->_data['comment'] = "It wants to... turn me into something else. That's not too terrible is it? Most people would give anything to be turned into something else.";

			$this->_id = -1;

			// A single id has been supplied.
			// Check wether comment with that id does exist and load it
			if ( isset($array['id']) ) {

				if (trim($array['id']) === "" ||
					!is_numeric($array['id'])) {
					die("ERROR: Id is empty or not numeric! (Comment::__construct())");
				}

				$this->_id = $array['id'];

				if ( !$this->doesExist() ) {
					die("ERROR: Comment " . $this->_id . " not found! (Comment::__construct())");
				}

				$this->loadEntry();

			// Data has been supplied.
			// Validate data and load current instance with supplied data
			} elseif( isset($array['data']) ) {

				if (!isset($array['data']['author']) ||
					!isset($array['data']['a_id']) ||
					!isset($array['data']['comment'])) {
					die("ERROR: Missing data! (Comment::__construct())");
				} else {

					// Check id	
					if (isset($array['data']['id'])) {
						if (trim($array['data']['id']) === "" ||
							!is_numeric($array['data']['id'])) {
							die("ERROR: Id is empty or not numeric! (Comment::__construct())");
							}
						$this->_id = $array['data']['id'];

						// Check if comment with supplied id exists
						if ( !$this->doesExist() ) {
							die("ERROR: Comment " . $this->_id . " not found! (Comment::__construct())");
						}
					}

					// Check article-id	
					if (trim($array['data']['a_id']) === "" ||
						!is_numeric($array['data']['a_id'])) {
						die("ERROR: a_id is empty or not numeric! (Comment::__construct())");
						}
					$this->_data['a_id'] = $array['data']['a_id'];

					// Check if article with supplied id exists
					require_once("models/Article/Article.class.php");	
					$article = new Article( array("id" => $this->_data['a_id']) );

					// Check author
					if (trim($array['data']['author']) === "") {
						die("ERROR: Author is empty! (Comment::__construct())");
					}
					$this->_data['author'] = $array['data']['author'];

					// Check mail
					$this->_data['mail'] = $array['data']['mail'];

					// Check www
					$this->_data['www'] = $array['data']['www'];

					// Check comment
					if (trim($array['data']['comment']) === "") {
						die("ERROR: Comment is empty! (Comment::__construct())");
					}
					$this->_data['comment'] = $array['data']['comment'];

				}

			}

		} 

		public function loadEntry() {

			switch(Config::getOption("db_type")) {
				case "mysql":
					$query = "SELECT *, DATE_FORMAT(c_date, '%D %M %Y – %H:%i') as c_date 
							  FROM comments WHERE id = :id";
					break;

				case "sqlite":
					$query = "SELECT *, strftime('%d.%m.%Y – %H:%M', c_date) as c_date 
							  FROM comments WHERE id = :id";
					break;
			}
			$this->_data = DB::getOne($query, array(':id' => $this->_id));

			$options = array('htmlspecialchars', 'utf8_decode', 'stripslashes');
			Sanitize::process_array($this->_data, $options);

		}
		
		public function saveEntry() {
			if ($this->_id === -1) {
				$this->newEntry();
			} else {
				$this->updateEntry();
			}
		}
		
		public function newEntry() {
			switch(Config::getOption("db_type")) {
				case "mysql": 
					$query = "INSERT INTO comments 
								(a_id, author, mail, www, comment, c_date) 
							  VALUES 
								(:a_id, :author, :mail, :www, :comment, NOW())";
				break;
			
				case "sqlite": 
					$query = "INSERT INTO comments 
								(a_id, author, mail, www, comment, c_date) 
							  VALUES 
								(:a_id, :author, :mail, :www, :comment, DATETIME('NOW'))";
				break;
			}
			$params = array(
					':a_id' => $this->_data['a_id'], 
					':author' => $this->_data['author'],
					':mail' => $this->_data['mail'],
					':www' => $this->_data['www'],
					':comment' => $this->_data['comment']
			);
			DB::execute($query, $params);

			$this->_id = DB::lastId();
		}
		

		public function updateEntry() {
			$query = 'UPDATE comments SET 
						author=:author,
						mail=:mail,
						www=:www,
						comment=:comment
					  WHERE id=:id';
			$params = array(
				':author' => $this->_data['author'], 
				':mail' => $this->_data['mail'],
				':www' => $this->_data['www'],
				':comment' => $this->_data['comment'], 
				':id' => $this->_data['id']);
			DB::execute($query, $params);
		}

		public function deleteEntry() {
			$query = 'DELETE FROM comments
						WHERE id = :id';
			$params = array('id' => $this->_id);
			DB::execute($query, $params);
			die("#t");
		}
	} // <!-- end class ’Controller’ -->
?>
