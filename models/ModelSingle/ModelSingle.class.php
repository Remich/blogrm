<?php

    require_once("interfaces/iModel.interface.php");
    require_once("models/Model/Model.class.php");

    class ModelSingle extends Model implements iModel {

        /**
         * Return HTML output
         */
        public function display() {
        	
            $tpl = new View ();
            $tpl->setTemplate($this->_template);
            $tpl->setTemplateDir($this->_templateDir.$this->_name."/views/");
            $tpl->assign('data', array( $this->_data ));
            return $tpl->loadTemplate();    

        }

        public function doesExist() {

            $query = "SELECT COUNT(id) as no
                        FROM ".$this->_table."
                        WHERE id = :id LIMIT 1";
            $params = array(':id' => $this->_id);
            $data = DB::getOne($query, $params);

            if ($data['no'] === "0") {
                return false;
            } else {
                return true;
            }

        }

    } 

?>