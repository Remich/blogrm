<?php

    class Model {
    	
        protected $_data = null;
        protected $_request = null;
        protected $_template = "index";
        protected $_templateDir = "models/";
        protected $_name = "UnnamedModel";
        protected $_id = NULL;
        protected $_table = NULL;
        
        public function __construct() {
        }
        public function setTemplate($tpl) {
        	$this->_template = $tpl;
        }
        public function getTags($table) {
            $query = 'SELECT * FROM '.$table.' WHERE id_a = :id_a';
            return  DB::get($query, array(':id_a' => $this->_id));
        }

    }

?>
