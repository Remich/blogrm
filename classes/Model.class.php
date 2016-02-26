<?php
    class Model {
        
        protected $_data = null;
        protected $_request = null;
        protected $_template = "index";
        protected $_templateDir = "models/";
        protected $_name = "UnnamedModel";
        protected $_id = NULL;
        protected $_table = NULL;
        
        public function __construct($array = NULL) {
            if(isset($array['table'])) {

                if (trim($array['table']) === "") {
                    die('ERROR: Table name is empty. (Model::__construct())');
                }

                if(!Misc::doesTableExist($array['table'])) {
                    die('ERROR: Table '.$array['table'].' does not exist! (Model::__construct())');
                }

                $this->_table = $array['table'];

            }

            if(isset($array['id'])) {

                if (trim($array['id']) === "") {
                    die('ERROR: ID is empty! (Model::__construct())');
                }

                if (!is_numeric($array['id'])) {
                    die('ERROR: ID is not numeric! (Model::__construct())');
                }

                $this->_id = $array['id'];
            }
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
