<?php
    class Config  {       
        private static $cfg = array(        
        
        	"debug" => true, // Debug Mode
        	"theme" => "blueappeal", //pineapple",
        	"db_type" => "sqlite", // mysql or sqlite
            "path_abs" => "/home/pepe/Desktop/html/wygiwys/",
            "session_name" => "wygiwys",
            
            "dbhost" => "localhost",
            "dbname" => "",
            "dbuser" => "",
            "dbpass" => "",
        		
        	"plugins" => array(
				"editor", "fileupload"
			),
        	"articles_per_page" => 7,
        ); 

        public static function getOption($key){  
            return self::$cfg[$key];  
        }           
    } 
?>
