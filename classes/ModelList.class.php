<?php

require_once("interfaces/iModel.interface.php");

class ModelList extends Model implements iModel {
	
	protected $_tag_id;
	protected $_query;
    protected $_params = NULL;

    /**
     * Return HTML output
     */
    public function display() {
        
        $tpl = new View ();
        $tpl->setTemplate($this->_template);
        $tpl->setTemplateDir($this->_templateDir.$this->_name."/views/");
        $tpl->assign('data', $this->_data);
        return $tpl->loadTemplate();

    }

    // TODO: evtl in TagManager auslagern
    protected function getWheres($table) {
    	
    	$tags = explode(".", $this->_tag_id);
    	$a_ids = array();
    	foreach($tags as $item) {
    		$query = 'SELECT id_a FROM '.$table.' WHERE id_b = :id_b';
    		$tmp = DB::get($query, array(':id_b'=>$item));
    		foreach($tmp as $item2) 
    			$a_ids[$item][] = $item2['id_a'];
    	}
    	if(sizeof($a_ids)>1)
    		$result = call_user_func_array('array_intersect',$a_ids);
    	else
    		foreach($a_ids as $item)
    			$result = $item;    	
    	
    	$first = true;
    	$wheres = "";
        $i = 0;
        $params = array();
    	foreach($result as $item) {
    		$wheres .= ($first ? "" : " OR ") . "id=:id".$i;
            $params['id'.$i++] = $item;
    		$first = false;
    	}
        return array($wheres, $params);
    }

	protected function getData() {

        if($this->_params === NULL) {
            return DB::get($this->_query);     
        } else {
            return DB::get($this->_query, $this->_params);
        }

    }
    
    protected function getDataWithPages($anzahl) {
    	// Limit the items,which will be displayed to the value of the entry in the config and make it flip

        if($this->_params === NULL) {
            $data = DB::get($this->_query);
        } else {
            $data = DB::get($this->_query, $this->_params);
        }
    	
    	$pages = new Pages(count($data), $anzahl);
    	if(@!isset($this->_request['jump']) OR $this->_request['jump'] == "" OR $pages->getStart($this->_request['jump']) < 0 ) {
    		$this->_request['jump'] = $pages->getPages();
        }
    	
    	if($this->_request['jump'] != "all") {
    		$this->_query .= ' LIMIT '.$pages->getStart($this->_request['jump']).', '.$anzahl;
    	}
    	
    	// Display flipping pages
    	if($pages->getPages() > 0) {
    		$this->_data['status']['flipping'] = $pages->getHtml($this->_request['jump'], $pages->getPages());
    	} else {
    		$this->_data['status']['flipping'] = '';
        }
    	
        if($this->_params === NULL) {
            return DB::get($this->_query);      
        } else {
            return DB::get($this->_query, $this->_params);
        }
    }

} 
