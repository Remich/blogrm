<?php

	/**
	*	Copyright 2010-2014 René Michalke.
	*/
    require_once("models/Bookmark/Bookmark.class.php");

	class ListOfBookmarks extends ModelList {
		
		protected $_name = "ListOfBookmarks";
		private $_trashQuery;
		private $_hidden = 0; // set to true in order to display also files, which have been hidden
		private $_hiddenQuery = 'hidden = 0';
		
		
		/**
		* Constructor
		*
		*/
		public function __construct($tag_id = null, $request = null) {
			
			
			$this->_request = $request;
			$this->_tag_id = $tag_id;
			
			if(!isset($this->_request['trashed'])) 
				$this->_data['status']['trashed'] = 0; 
			elseif($this->_request['trashed'] === "")
				$this->_data['status']['trashed'] = 0;
			else $this->_data['status']['trashed'] = $this->_request['trashed'];
			
			$this->_trashQuery = 'trashed = '.$this->_data['status']['trashed'];
			
			if(isset($_SESSION['hidden'])) {
				$this->_hidden = 1;
				$this->_hiddenQuery = '';
			}

			if(!isset($this->_request['sort'])) $this->_request['sort'] = 'date';
			if(!isset($this->_request['order'])) $this->_request['order'] = 'DESC';
			 
			$this->_data['status']['sort'] = $this->_request['sort'];
			$this->_data['status']['order'] = $this->_request['order'];
			
			$this->load();
		} // <!-- end function ’__construct()’ -->
		
		public function load() {
			$title = ( isset($this->request['search']) ? '' : 'Results for ' ).'&raquo;<span>'. ( (isset($this->_request['trashed']) AND $this->_request['trashed'] == 1) ? 'trashed' : 'All Bookmarks'  ).'</span>&laquo;';
			
			/**
			 * Creating the demanded SELECT Query and associated Title
			 */		
			
			if($this->_tag_id == null) {
				$this->_query = 'SELECT id FROM bookmark_items WHERE '.$this->_trashQuery.' AND '.$this->_hiddenQuery.' AND uid = 1 ORDER BY '.$this->_request['sort'].' '.$this->_request['order'];
			} else {			
				// TODO: Escape
				$this->_query = 'SELECT id FROM bookmark_items WHERE '.$this->getWheres("rel_bitems_btags").' AND '.$this->_trashQuery.' AND '.$this->_hiddenQuery.' AND uid = 1 ORDER BY '.$this->_request['sort'].' '.$this->_request['order'];
			
				$select = 'SELECT name FROM bookmark_tags WHERE id = :tid LIMIT 1';
				$params = array(
						':tid' => $this->_tag_id
				); $tag_title = DB::getOne($select, $params);
					
				if($tag_title == 0)
					$title = 'Error: Tag Not Found';
				else
					$title .= ' <a href="ajax.php?action=load&id=clear_all_filter" title="Clear Filter"> filtered by</a> &raquo;<span>Tag &rsaquo; '. $tag_title['name'] .'</span>&laquo;';
			}
			
			$this->_data['title'] = $title;
			
			$data = $this->getDataWithPages(Config::getOption('bookmarks_per_page'));
			
			if(!sizeof($data)) {
				$tmp = new Bookmark();
				$this->_data['content'][0] = $tmp->display();
			} else 
				foreach($data as $key => $item) {
	                $tmp = new Bookmark(array('id'=>$item['id']));
	                $this->_data['content'][$key] =  $tmp->display();
	            }
		}		
		
	} // <!-- end class ’Controller’ -->   
?>
