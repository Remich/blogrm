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

    }

?>
