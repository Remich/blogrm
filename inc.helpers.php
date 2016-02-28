<?php

	function get_theme_folder() {
		return "themes/".Config::getOption("theme")."/";
	}

	function pre($var) {
		echo "<pre>";
		print_r($var);
		echo "</pre>";
	}

	function dump($var) {
		echo "<pre>";
		var_dump($var);
		echo "</pre>";
	}

	function shortenStr($string, $length, $wordCut = 1) {

		if(strlen($string) > $length) {
			$string = mb_substr($string,0,$length, 'UTF-8').'…';
			if($wordCut) {
				$string_ende = strrchr($string, " ");
				$string = str_replace($string_ende,"…", $string);
			}
			
		} 

		return $string;

	}

	function returnBytes($val) {
	
		$val = trim($val);
		$last = strtolower($val[strlen($val)-1]);
		
		switch($last) {
	
			case 'g': $val *= 1024;
			case 'm': $val *= 1024;
			case 'k': $val *= 1024;
			
		} 

		return $val;
		
	}

	function doesTableExist($table) {
		switch(Config::getOption("db_type")) {
			case "mysql": 
				$query = "SHOW TABLES LIKE :name";
			break;
		
			case "sqlite": 
				$query = "SELECT name FROM sqlite_master WHERE type='table' AND name=:name";
			break;
		}
		$params = array(':name' => $table);
		$data = DB::get($query, $params);

		if (sizeof($data) > 0) {
			return true;
		} else {
			return false;
		}
	}

?>