<?php

require_once("interfaces/iModel.interface.php");

class ModelList extends Model implements iModel {
	
	protected $_tag_id;
	protected $_query;

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
    	foreach($result as $item) {
    		$wheres .= ($first ? "" : " OR ") . "id=".$item;
    		$first = false;
    	}
    	return $wheres;
    }

	protected function getData() {
    	$data = DB::get($this->_query);    	
    	return DB::get($this->_query);    	
    }
    
    protected function getDataWithPages($anzahl) {
    	// Limit the items,which will be displayed to the value of the entry in the config and make it flip
    	$data = DB::get($this->_query);
    	
    	$pages = new Pages(count($data), $anzahl);
    	if(@!isset($this->_request['jump']) OR $this->_request['jump'] == "" OR $pages->getStart($this->_request['jump']) < 0 )
    		$this->_request['jump'] = $pages->getPages();
    	
    	if($this->_request['jump'] != "all") {
    		$this->_query .= ' LIMIT '.$pages->getStart($this->_request['jump']).', '.$anzahl;
    	
    	}
    	
    	// Display flipping pages
    	if($pages->getPages() > 0)
    		$this->_data['status']['flipping'] = $pages->getHtml($this->_request['jump'], $pages->getPages());
    	else
    		$this->_data['status']['flipping'] = '';
    	
    	return DB::get($this->_query);    	
    }

} 
