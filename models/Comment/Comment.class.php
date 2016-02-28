<?php

	/**
	 * @file
	 * Copyright 2010-2014 René Michalke.
 	 */
	
	require_once('interfaces/iDBContentStatic.interface.php');
    require_once("models/ModelSingle/ModelSingle.class.php");
	
	class Comment extends ModelSingle implements iDBContentStatic {
		
		protected $_name = "Comment";
		
		public function __construct($array = null) {
			
			if($array == null || sizeof($array) == 0) {
				$this->_data['c_id'] = "Das ist der Default Comment Title";
				$this->_data['author'] = "Author";
				$this->_data['comment'] = "Lorem Ipsum Comment";
				$this->newEntry();
			}
			if(isset($array['id']))
				$this->load($array['id']);
				
			if(isset($array['data'])) {
				$this->_data = $array['data'];
				$this->newEntry();
			}
			
		} 
		
		public function newEntry() {
			$query = "INSERT INTO comment 
						(a_id, author, mail, www, comment, c_date) 
					  VALUES 
						(:a_id, :author, :mail, :www, :comment, NOW())";
			$params = array(
					':a_id' => $this->_data['a_id'], 
					':author' => (isset($this->_data['author']) ? $this->_data['author'] : "Coding Monkey"),
					':mail' => (isset($this->_data['mail']) ? $this->_data['mail'] : "codingmonkey@renemichalke.de"),
					':www' => (isset($this->_data['www']) ? $this->_data['www'] : "http://www.renemichalke.de"),
					':comment' => (isset($this->_data['comment']) ? $this->_data['comment'] : 'Default Comment.')
			);
			DB::execute($query, $params);
			
			//$this->load($lastID);
		}
		
	
		public function load($id) {

			switch(Config::getOption("db_type")) {
				case "mysql": 
					$query = "SELECT *, DATE_FORMAT(c_date, '%D of %M %Y – %H:%i') as c_date 
						  FROM comment WHERE id = :id";
				break;
			
				case "sqlite": 
					$query = "SELECT *, strftime('%d %m %Y – %H:%M', c_date) as c_date 
						  FROM comment WHERE id = :id";
				break;
			}
			
			$this->_data = DB::getOne($query, array(':id' => $id));
						
			if(sizeof($this->_data) == 0)
				$this->set(  array(
					'id' => $id, 
					'author' => "Fehler: Comment mit id ".$id." nicht gefunden",
					'mail' => "Fehler: Comment mit id ".$id." nicht gefunden",
					'www' => "Fehler: Comment mit id " .$id. " nicht gefunden",
					'comment' => "Fehler: Comment mit id ".$id." nicht gefunden",
					'c_date' => "Fehler: Comment mit id ".$id." nicht gefunden"
				) );
				
			//$options = array('stripslashes');
			$options = array('htmlspecialchars', 'utf8_decode', 'stripslashes');
			Sanitize::process_array($this->_data, $options);
		}
		
		public function save() {
			$query = 'UPDATE comment SET 
						author=:author,
						mail=:mail,
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

	} // <!-- end class ’Controller’ -->
?>
