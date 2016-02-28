<?php

	/**
	*	Copyright 2010-2014 René Michalke.
	*/
    require_once("models/ModelSingle/ModelSingle.class.php");

	class TagCloud extends ModelSingle {
		
		private $_tags = null;
		private $_min = 9; // Minimum Fontsize
		private $_max = 128; // Maximum Fontsize
		private $_table_relation = null;
		private $_table_tags = null;
		private $_tag_id = null;
		private $_page = null;
		
		public function __construct($tag_id = null) {
			if ($tag_id != NULL) {
				if (trim($tag_id) === "" ||
					!is_numeric($tag_id)) {
					die("ERROR: Tag-ID is empty or not numeric! (TagCloud::__construct()");
				}

				$this->_tag_id = $tag_id;		
			}	
		}
		public function setTableRelation($table = null) {
			if($table == null) {
				die('ERROR: Missing table! (TagCloud::setTableRelation())');
			}
			if (trim($table) === "") {
				die('ERROR: Table-name is empty! (TagCloud::setTableRelation())');
			}
			if (!doesTableExist($table)) {
				die('ERROR: Table '.$table.' does not exist! TagCloud::setTableRelation())');
			}
			
			$this->_table_relation = $table;			
		}
		public function setTableTags($table = null) {
			if($table == null) {
				die('ERROR: Missing table! (TagCloud::setTableTags())');
			}
			if (trim($table) === "") {
				die('ERROR: Table-name is empty! (TagCloud::setTableTags())');
			}
			if (!doesTableExist($table)) {
				die('ERROR: Table '.$table.' does not exist! TagCloud::setTableTags())');
			}
				
			$this->_table_tags = $table;
		}
		public function setPage($page = null) {
			if($page === null) {
				die('ERROR: missing page! (TagCloud::setPage())');
			} 
			if (trim($page) === "") {
				die('ERROR: page is empty! (TagCloud::setPage())');
			}
		
			$this->_page = $page;
		}
		public function setFontMax($max) {
			$this->_max = $max;
		}
		public function setFontMin($min) {
			$this->_min = $min ;
		}
		public function generate() {

			if($this->_tag_id === null) {
				$query = 'SELECT * FROM '.$this->_table_tags.' WHERE uid = 1 ORDER BY name ASC';
				$this->_tags = DB::get($query);
			} else {
				$query = "SELECT * FROM ".$this->_table_tags." as t3 JOIN (SELECT DISTINCT(id_b) FROM ".$this->_table_relation. " AS t1 JOIN (SELECT id_a FROM ".$this->_table_relation." WHERE id_b = :id_b) AS t2 ON t2.id_a = t1.id_a WHERE id_b != :id_b) as t4 ON t4.id_b = t3.id";

				$params = array(":id_b" => $this->_tag_id);
				$this->_tags = DB::get($query, $params);
			}
				
			// count occurences
			foreach($this->_tags as $key => $item) {
				$query = 'SELECT
							COUNT(*) as quantity
						  FROM
							'.$this->_table_relation.'
						  WHERE
							id_b = :id_b LIMIT 1';
				$data = DB::getOne($query, array(':id_b' => $item['id']));
				$this->_tags[$key]['occurences'] = $data['quantity'];
			}
			
		}
			
		
		public function display() {
			if( count($this->_tags) === 0) {	
				return "Nicht genug Daten für die TagCloud";
			} else {

				$most = 1;
				foreach($this->_tags as $item) {
					if($item['occurences'] > $most) {
						$most = $item['occurences'];
					}
				}

				$tags = array();

				foreach($this->_tags as $key => $item) {
					$font = ceil(  ($item['occurences'] / $most ) * $this->_max );
					$tags[] = ' <a style="margin-right: 15px; font-size:'.$font.'px;" href="index.php?tag_id='.$item['id'].'" title="'.$item['occurences'].' Entries">'.$item['name'].'</a>';
				}
				
				return '<section id="tagcloud">'."\n<p>" . implode("", $tags) . "</p>\n</section>";
			}
		}
	} 
?>
