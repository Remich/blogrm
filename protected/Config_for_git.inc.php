<?php
    class Config  {       
        private static $cfg = array(        
        
        	"debug" => true, // Debug Mode
        	// "theme" => "blueappeal",
            "theme" => "pineapple",
            // "theme" => "deadlanguage",
        	"db_type" => "sqlite", // mysql or sqlite
            // "db_type" => "mysql",
            "path_abs" => "/home/pepe/Desktop/html/wygiwys/",
            "session_name" => "wygiwys",
            
            "dbhost" => "localhost",
            "dbname" => "db_not_for_git",
            "dbname" => "", 
            "dbname" => "" 
            "dbuser" => "",
            "dbpass" => "",

        	"plugins" => array(
                array(
                    "key"            => "admin-panel",
                    "name"           => "Admin-Panel",
                    "active"         => true,
                    "display_switch" => false,
                ),
                array(
                    "key"            => "editor",
                    "name"           => "Editor",
                    "active"         => true,
                    "display_switch" => true,
                ),
                array(
                    "key"            => "fileupload",
                    "name"           => "Fileupload",
                    "active"         => false,
                    "display_switch" => true,
                ),
			),
        	"articles_per_page" => 7,

            "page_title" => "René Michalke – Blog",
            "page_language" => "de"

        ); 
        public static function getOption($key){  
            return self::$cfg[$key];  
        }           
        public static function getActivePlugins() {
            return array_filter(
                self::getOption('plugins'), 
                function($item) { 
                    if($item['active'] === true) return $item;
                }
            );
        }
        public static function getActivePluginSwitches() {
            return array_filter(
                self::getOption('plugins'), 
                function($item) { 
                    if($item['active'] === true && $item['display_switch'] === true) 
                        return $item;
                }
            );
        }
    } 
?>
