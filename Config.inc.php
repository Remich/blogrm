<?php
    class Config  {       
        private static $cfg = array(        
        
        	"debug" => false, // Debug Mode
        	"theme" => "pineapple",
        	"db_type" => "sql",
            /*"siteurl"=> "http://localhost/public/MVC Framework/page/",
            "serverdir" => "/Users/pepe-roni/Sites/public/MVC Framework/page/",*/
            "path_abs" => "/home/pepe/html/wygiwys/",
            
            "dbhost" => "localhost", //"192.168.178.64",
            "dbname" => "rm",
            "dbuser" => "rm",
            "dbpass" => "xZvyCcqfvxZAtbJK",
        		
        	"plugins" => array(
				"editor", "fileupload" /* ,"sortable", "admin-panel"*/
			),
        	"bookmarks_per_page" => 30,
        	"articles_per_page" => 7,
        	//"dbport" => 
            
            /*"title"  => "MVC Framework",

            "newsentrys"  => 3,
            "internentrys"  => 10,
            "gbookentrys"  => 10,
            "contactentrys"  => 10,
            
            "kontakt-adresses"  => array('oicelot@web.de', 'egor-trawkin@gmx.de')*/
            
        ); 
        public static function getOption($key){  
            return self::$cfg[$key];  
        }           
    } 
?>
