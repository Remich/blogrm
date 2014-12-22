<?php

	/**
	*	Copyright 2010-2013 René Michlke
	*
	*	This file is part of RM Internet Suite.
	*/

	/**
	* class Mailbox
	*
	* Some methods for imap usage
	*/
	class Mailbox {
	
		private $aid = NULL;
		private $mbox = NULL;
		private $data = NULL;
		
		function __construct ($aid = NULL, $test = NULL) {
		
			if($aid === NULL)
				die('Software Error: Missing paramater $aid in Mailbox::__construct()');
		
			$this->aid = $aid;
			$this->data = $this->getAccountInfo($test);	
			$this->createServerAddressStr();
			
			
			/*$this->mbox = @imap_open($this->data['server_addr_str'], $this->data['server_username'], $this->data['server_password']);
			imap_errors();
			imap_alerts();
			if($this->mbox === false)
				die("Can't connect to mailbox " .$this->data['server_addr_str'] ."\n");*/
			
		}
		
		public function connect($data = NULL) {
		
			$this->mbox = @imap_open($this->data['server_address_str'], $this->data['username'], $this->data['password']);
			imap_errors();
			imap_alerts();
			
			return $this->mbox;
		
		}
		
		public function createServerAddressStr() {
		
				$this->data['server_address_str'] = '{' . $this->data['server_address']
											.':'.$this->data['server_port']
											.'/'.$this->data['server_protocol']
											.(($this->data['connection_security'] == '') ? '' : '/'.$this->data['connection_security'])
											.(($this->data['server_novalidate'] == '1') ? '/novalidate-cert' : '')
											.(($this->data['secure_authentification'] == '1') ? '/secure' : '')
											.'}';
											
		}
		
		public function getAccountInfo($test = NULL) {
			
			$table = ($test === NULL) ? 'mails_accounts' : 'mails_accounts_test';
		
			$query = 'SELECT *
					  FROM '.$table.'
					  WHERE id = :id LIMIT 1';
			$params = array(
				':id' => $this->aid
			);
			$data = DB::GetOne($query, $params);
			
			return $data;
		}
		
		public function getHeaders() {
		
			imap_headers($this->mbox);	
			
		}
		
		public function getHeader($uid = NULL) {
		
			if($uid === NULL)
				die('Software Error: Missing paramater $uid in Mailbox::getHeader()');
				
			return imap_header($this->mbox, imap_msgno($this->mbox, $uid));
			
		}
		
		public function getNumMsg() {
			
			return imap_num_msg($this->mbox);
		
		}
		
		public function getStatus($flag) {
		
			return imap_status($this->mbox, $this->data['server_addr_str']."INBOX", $flag);
		
		}
		
		public function getMsgNo($uid = NULL) {
		
			if($uid === NULL)
				die('Software Error: Missing paramater $uid in Mailbox::getMsgNo()');
				
			return imap_msgno($this->mbox, $uid);
			
		}
		
		public function getUid($msgNo = NULL) {
		
			if($msgNo === NULL)
				die('Software Error: Missing paramater $msgNo in Mailbox::getUid()');
				
			return imap_uid($this->mbox, $msgNo);
			
		}
		
		public function getStruct($uid = NULL) {
		
			if($uid === NULL)
				die('Software Error: Missing paramater $uid in Mailbox::getStruct()');
			
			return imap_fetchstructure($this->mbox, $uid, FT_UID);	
			
		}
		
		public function getPartStruct($uid = NULL, $part = NULL) {
		
			if($uid === NULL)
				die('Software Error: Missing paramater $uid in Mailbox::getPartStruct()');
				
			if($part === NULL)
				die('Software Error: Missing paramater $part in Mailbox::getPartStruct()');
				
			return imap_bodystruct($this->mbox, imap_msgno($this->mbox, $uid), $part);
			
		}
		
		// return as specific part of the mail
		public function getPart($uid = NULL, $partNumber = NULL, $encoding = NULL) {
		
			if($uid === NULL)
				die('Software Error: Missing paramater $uid in Mailbox::getPart()');
				
			if($partNumber === NULL)
				die('Software Error: Missing paramater $partNumber in Mailbox::getPart()');
			
			if($encoding === NULL)
				die('Software Error: Missing paramater $encoding in Mailbox::getPart()');
			

			$data = imap_fetchbody($this->mbox, $uid, $partNumber, FT_UID);
			
			switch ($encoding) {
		
				case 3: return imap_base64($data);
				case 4: return imap_qprint($data);
				default: return $data;
				
				/*
				case 0:
				case 1:
					return imap_8bit($data);
				case 2:
					return imap_binary($data);
				case 3:
					return imap_base64($data);
				case 4:
					return imap_qprint($data); // quoted_printable_decode($message);*/
			
			}
		
		}
		
		public function getBody($uid = NULL, $partNumber = NULL) {
		
			if($uid === NULL)
				die('Software Error: Missing paramater $uid in Mailbox::getBody()');
				
			if($partNumber === NULL)
				die('Software Error: Missing paramater $partNumber in Mailbox::getBody()');
		
			return imap_fetchbody($this->mbox, $uid, $partNumber, FT_UID);	
			
		}
		
		public function delete( $uid = NULL ) {
		
			if($uid === NULL)
				die('Software Error: Missing paramater $uid in Mailbox::deleteMail()');
			
				
			return imap_delete( $this->mbox , $this->getMsgNo($uid));
			
		}
		
		public function undelete( $uid = NULL ) {
		
			if($uid === NULL)
				die('Software Error: Missing paramater $uid in Mailbox::deleteMail()');
			
				
			return imap_undelete( $this->mbox , $this->getMsgNo($uid));
			
		}
		
		public function expunge() {
			
			return imap_expunge( $this->mbox );
		
		}
		
		public function close() {
		
			if( !imap_close( $this->mbox ) ) die('Error: Closing Mailbox Connection');
			else return 1;	
			
		}
		
		public function getFolders() {
			
			if($this->mbox !== false)
				return imap_list($this->mbox, $this->data['server_addr_str'], "*");
		
		}
	
		
	} // <!-- end class ’Mailbox’ -->
	
?>
