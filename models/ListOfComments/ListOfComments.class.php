<?php

	/**
	*	Copyright 2010-2014 René Michalke.
	*/
    require_once("models/Comment/Comment.class.php");
    require_once("models/ModelList/ModelList.class.php");

	class ListOfComments extends ModelList {
		
		protected $_name = "ListOfComments";
		protected $_article = NULL;
		
		public function __construct($article_id = NULL) {


			if($article_id === NULL) {
				die('ERROR: Missing article_id! (ListOfComments::__construct())');
			}

			if (trim($article_id) === "" ||
				!is_numeric($article_id)) {
				die("ERROR: article_id is empty or not numeric! (ListOfComments::__construct())");
			}


			require_once("models/Article/Article.class.php");	
			$article = new Article( array("id" => $article_id) );

			$this->_article = $article_id;

			$this->load();
		}
		
		public function load() {

			$this->_query = 'SELECT * FROM comments WHERE a_id = :a_id ORDER BY c_date DESC';
			$this->_params = array(':a_id' => $this->_article);

			// Get Data From DB
			$data = $this->getDataWithPages(Config::getOption('articles_per_page'));
			
			// Create Instances of Comments
			if(!sizeof($data)) {
				$this->_data['content'] = array();
			} else {
				foreach($data as $key => $item) {
	                $tmp = new Comment(array('id'=>$item['id']));
	                $this->_data['content'][$key] =  $tmp->display();
	            }
	    	}

		}		
		
	}
?>
