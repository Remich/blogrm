<?php

	/**
	*	Copyright 2010-2013 René Michalke
	*
	*	This file is part of RM Internet Suite.
	*/
	
	
	/**
	* class Misc
	*
	* miscellaneous functions
	*/
	class Misc {
	
		// preformatted data
		public static function pre($var) {
		
			echo "<pre>";
			print_r($var);
			echo "</pre>";
			
		}
		
		public static function dump($var) {
			echo "<pre>";
			var_dump($var);
			echo "</pre>";
		}
	
		public static function shortenStr($string, $length, $wordCut = 1) {
	
			if(strlen($string) > $length) {
		
				$string = mb_substr($string,0,$length, 'UTF-8').'…';
				
				if($wordCut) {
				
					$string_ende = strrchr($string, " ");
					$string = str_replace($string_ende,"…", $string);
					
				}
				
			} return $string;
		}
		
		public static function returnBytes($val) {
		
			$val = trim($val);
			$last = strtolower($val[strlen($val)-1]);
			
			switch($last) {
		
				case 'g': $val *= 1024;
				case 'm': $val *= 1024;
				case 'k': $val *= 1024;
				
			} return $val;
			
		}
		
		public static function in_arrayi($needle, $haystack) {
		
		    return in_array(strtolower($needle), array_map('strtolower', $haystack));
		    
		}
		
		// tags LIKE: filter wrong results and return new filter string to add to qury
		public static function filterWrongResults($query, $tag, $caseInsensitive = NULL) {
	
			$data = DB::Get($query);
			$wrong_items = array();
	
			foreach($data as $key => $item) {

				$tags = explode('#', $item['tags']);
				
				if($caseInsensitive == NULL) {
					if(!in_array($tag, $tags))
						$wrong_items[] = $item['id'];
				} else
					if(!Misc::in_arrayi($tag, $tags))
						$wrong_items[] = $item['id'];
			
			}

			$str = '';
			foreach($wrong_items as $item) $str .= ' AND id != '.$item;
	
			return $str;
	
		}
		
		public static function getOption($table, $needle, $where) {
		
			foreach($where as $key => $item)
				@$str .= $key.' = '.$item.' ';
		
			$query = 'SELECT
						'.$needle.'
					  FROM
					  	'.$table.'
					  WHERE
					  	'.$str.'
					  LIMIT 1';
			$data = DB::getOne($query);
			
			return $data[$needle];
		
		}
		
		public static function getRow($table, $where) {
		
			foreach($where as $key => $item)
				@$str .= $key.' = '.$item.' ';
		
			$query = 'SELECT
						*
					  FROM
					  	'.$table.'
					  WHERE
					  	'.$str.'
					  LIMIT 1';
			$data = DB::getOne($query);
			
			return $data;
		
		}
		
		public static function getImage($url, $hash, $folder) {
	
			if(!is_dir($folder))
				mkdir($folder, 0777);
		
			$file =  $folder.$hash.'.png';
		
			$path = Url::getCurrentServerPath();
			$script = $path.'extensions/wkhtmltoimage-amd64';
			
			$command = 'timeout 60s xvfb-run --server-args="-screen 0, 1120x700x24" '.escapeshellarg($script).' --use-xserver --width 1120 --height 700 --stop-slow-scripts --encoding utf8 --format png --disable-smart-width --quality 100 '.escapeshellarg($url).' '.escapeshellarg($file);
			echo '<br>Running Command: '.$command.' …<br><br>';
	
			$last_line = system($command, $output);
		
			echo '<br><br>Command returned: ' . $output.'<br>';
		
			if(file_exists($file)) {
				echo '<br>Creating Thumbnail…';
				$src = imagecreatefrompng($file);
				$dst = imagecreatetruecolor(160, 100);
				imagecopyresampled( $dst , $src , 0 , 0 , 0 , 0 , 160 , 100 , 1120 , 700);
				imagepng($dst, $file, 0);
				imagedestroy($dst);
			}
	
		}
	
	} // <!-- end class ’Misc’ -->
	
?>
