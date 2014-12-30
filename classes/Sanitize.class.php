<?php

	/**
	*	Copyright 2010-2013 René Michlke
	*
	*	This file is part of RM Internet Suite.
	*/
	
	/*
	* class Sanitize
	*
	* Some methods to sanitize data
	*/
	class Sanitize {

		public static function process_array(&$array, $options) {
			if (in_array("htmlspecialchars", $options)) {
				$array = self::htmlspecialchars($array);
			}
			if (in_array("utf8_encode", $options)) {
				$array = self::utf8_encode($array);
			}
			if (in_array("stripslashes", $options)) {
				$array = self::stripslashes($array);
			}
			if (in_array("make_save_str_in", $options)) {
				$array = self::make_save_str_in($array);
			}
			if (in_array("htmlspecialchars_decode", $options)) {
				$array = self::htmlspecialchars_decode($array);
			}
			if (in_array("purify", $options)) {
				$array = self::purify($array);
			}
	}		
		
		public static function htmlspecialchars($array) {
			foreach($array as $key => $item) {
				$array[$key] = is_array($item)
				? self::htmlspecialchars($item)
				: htmlspecialchars($item, ENT_QUOTES | ENT_DISALLOWED, 'UTF-8');
			} 
			return $array;
		}
		public static function purify($array) {
			foreach($array as $key => $item) {
				$array[$key] = is_array($item)
				? self::purify($item)
				: self::purify_check($item);
			} 
			return $array;
		}

		public static function purify_check($string) {
			require_once 'libs/htmlpurifier-4.6.0/library/HTMLPurifier.auto.php';

			$config = HTMLPurifier_Config::createDefault();
			$purifier = new HTMLPurifier($config);
			$config->set('Core.Encoding', 'UTF-8');
			$clean_html = $purifier->purify($string);

			return $clean_html;
		}

		public static function htmlspecialchars_decode($array) {
			foreach($array as $key => $item) {
				$array[$key] = is_array($item)
				? self::htmlspecialchars_decode($item)
				: htmlspecialchars_decode($item, ENT_QUOTES | ENT_DISALLOWED);
			} 
			return $array;
		}

		public static function utf8_encode($array) {
			foreach($array as $key => $item)
				$array[$key] = is_array($item)
				? self::utf8_encode($item)
				: utf8_encode($item);
			return $array;
		}
		
		public static function utf8_decode($array) {
			foreach($array as $key => $item)
				$array[$key] = is_array($item)
				? self::utf8_decode($item)
				: utf8_decode($item);
			return $array;
		}
		public static function stripslashes($array) {
			foreach($array as $key => $item)
				$array[$key] = is_array($item)
				? self::stripslashes($item)
				: stripslashes($item);
			return $array;
		}
		
		public static function addslashes($array) {
			foreach($array as $key => $item)
				$array[$key] = is_array($item)
				? self::addslashes($item)
				: addslashes($item);
			return $array;
		}
		
		public static function trim($array) {
			foreach($array as $key => $item)
				$array[$key] = is_array($item)
				? self::trim($item)
				: trim($item);
			return $array;
		}

		
		public static function make_save_str_in($array) {
			#$string = self::addslashes(self::stripslashes($array)); // stripslashes to avoid double slashing
			$string = self::trim($array);
			return $string;
		}
				
		public static function StringToPost($string){        
            $array = explode('&', $string);
            foreach($array as $key => $item) {
                $single = explode("=", $item);
                $array[$single[0]] = urldecode($single[1]);
                unset($array[$key]);
            }
            return $array;
        }
        
        public static function FileName($string) {
        	return preg_replace('/[^a-z\d_]/iu', '', $string);
        }
                
        public static function RemoveTagsFromPre($string) {
        	require_once("libs/simple_html_dom.php");   
	
           	$html = new simple_html_dom();
        	$html->load($string, true, false);   
        	foreach($html->find('pre') as $pre) {
        		$pre->innertext = str_replace("</li>", "\n", $pre->innertext);
        		$pre->innertext = preg_replace("/<(.|\n)*?>/", "", $pre->innertext);
        	} 
        	return $html->save();
        }
        
       #strippedValue.replace(/<li[^>]*>([\s\S]*?)<\/li>/ig, "* $1\n");
		
	}  // <!-- end class ’Sanitize’ -->
	
?>
