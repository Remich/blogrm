<?php

	/**
	*	Copyright 2010-2013 René Michlke
	*
	*	This file is part of RM Internet Suite.
	*/

	/**
	* class Mail
	*
	* Some methods for email usage / parsing
	*/
	class Mail {
	
		private $uid = NULL;
		private $msgNo = NULL;
		private $mbox = NULL;
		private $header = NULL;
		private $struct = NULL;
		private $mail = array( );
		
		function __construct ($mbox, $uid) {
		
			$this->uid = $uid;
			$this->msgNo = $mbox->getMsgNo($this->uid);
			$this->mbox = $mbox;
			
		}
		
		public function getMail() {
		
			return $this->mail;	
			
		}
		
		
		// return supported encodings in lowercase.
		public static function mb_list_lowerencodings() { 
	
			$r = mb_list_encodings();
		
			for ($n = sizeOf($r); $n--; ) { 
		
				$r[$n] = strtolower($r[$n]); 
			
			} return $r;
		
		}

		// Receives a string with a mail header and returns it decoded to a specified charset.
		// If the charset specified into a piece of text from header isn't supported by "mb", 
		// the "fallbackCharset" will be used to try to decode it.
		public static function decodeMimeString($mimeStr, $inputCharset = 'utf-8', $targetCharset = 'utf-8', $fallbackCharset = 'iso-8859-1') {
	
			
	
			$encodings = Mail::mb_list_lowerencodings();
			$inputCharset = strtolower($inputCharset);
			$targetCharset = strtolower($targetCharset);
			$fallbackCharset = strtolower($fallbackCharset);

			$decodedStr = '';
			$mimeStrs = imap_mime_header_decode($mimeStr);
		
			for ($n = sizeOf($mimeStrs), $i = 0; $i < $n; $i++) {
			
				$mimeStr = $mimeStrs[$i];
				
				$mimeStr->charset = strtolower($mimeStr->charset);
				$mimeStr->text = imap_utf8($mimeStr->text);
			
					
				if (($mimeStr == 'default' && $inputCharset == $targetCharset) || $mimeStr->charset == $targetCharset)
					$decodedStr .= $mimeStr->text;
				else 
					$decodedStr .= mb_convert_encoding($mimeStr->text, $targetCharset, (in_array($mimeStr->charset, $encodings) ? $mimeStr->charset : $fallbackCharset));
				
			} return $decodedStr;
		
		}
		
		// flattens the mailstructure of the mail's parts
		public function flattenParts($messageParts, $flattenedParts = array(), $prefix = '', $index = 1, $fullPrefix = true) {

			foreach($messageParts as $part) {
	
				$flattenedParts[$prefix.$index] = clone($part);
		
				if(isset($part->parts)) {
		
					if($part->type == 2)
						$flattenedParts = $this->flattenParts($part->parts, $flattenedParts, $prefix.$index.'.', 0, false);
					elseif($fullPrefix)
						$flattenedParts = $this->flattenParts($part->parts, $flattenedParts, $prefix.$index.'.');
					else
						$flattenedParts = $this->flattenParts($part->parts, $flattenedParts, $prefix);

					unset($flattenedParts[$prefix.$index]->parts);
			
				} $index++;
			} return $flattenedParts;
	
		}
		
		// checks wether a mail hast attachments
		private function hasAttachment() {
			
			if(@$this->struct->parts) {
	
				$flattenedParts = $this->flattenParts($this->struct->parts);
				
				$found = 0;
		
				foreach($flattenedParts as $partNumber => $part) 
					if($part->ifdisposition && ($part->disposition == 'attachment' || $part->disposition == 'ATTACHMENT')) return 1;
		
				return 0;
		
			} else return 0;
	
		}
		
		// returns filename of a mailpart, mailpart should be an attachment
		function getFilenameFromPart($part) {

			$filename = '';

			if($part->ifdparameters)
				foreach($part->dparameters as $object)
					if(strtolower($object->attribute) == 'filename') 
						$filename = $object->value;

			if(!$filename && $part->ifparameters)
				foreach($part->parameters as $object)
					if(strtolower($object->attribute) == 'name')
						$filename = $object->value;
			
			return $filename;

		}
		
		private function parseHeaderAddr($obj = NULL) {
		
			if($obj === NULL) 
				die('Software Error: Missing paramater $obj in Mail::parseHeaderAddr()');
		
			foreach($obj as $key => $item)
				@$str .= (($key > 0) ? ', ':'').((isset($item->personal)) 
					? $this->decodeMimeString($item->personal).' ' : '') . '&lt;'.$item->mailbox.'@'.$item->host.'&gt;';
		
			return $str;
			
		}	
		
		private function parseHeaderAddr2($obj = NULL) {
		
			$ar = array();
			
			if($obj === NULL) 
				return $ar;
		
			
			foreach($obj as $item) {
				$key = sizeof($ar);
				$ar[$key]['name'] = (isset($item->personal)) ? $this->decodeMimeString($item->personal).' ' : '';
				$ar[$key]['address'] = $item->mailbox.'@'.$item->host;
			}
			return $ar;
			
		}	
		
		private function parseHeaderAddr3($obj = NULL) {
		
			if($obj === NULL) 
				die('Software Error: Missing paramater $obj in Mail::parseHeaderAddr3()');
		
			foreach($obj as $key => $item) {
			
				if(isset($item->personal) && trim($item->personal) != '')
					@$str .= (($key > 0) ? ', ':'').Mail::decodeMimeString($item->personal);
				else
					@$str .= (($key > 0) ? ', ':'').'&lt;'.$item->mailbox.'@'.$item->host.'&gt;';
				
			}
		
			return $str;
			
		}
		
		public function parseHeader() {
		
			$this->header = $this->mbox->getHeader($this->uid);
			$this->struct = $this->mbox->getStruct($this->uid);
			
			$this->mail["uid"] = $this->uid;
			$this->mail['msgNo'] = $this->msgNo;
		
			if(@$this->header->to)
				$this->mail['toAddr'] = $this->parseHeaderAddr($this->header->to);
			
			if(@$this->header->from)
				$this->mail['fromAddr'] = $this->parseHeaderAddr($this->header->from);
			
			$this->mail['fromAddr_former'] = ((isset($this->header->from[0]->personal)) ? $this->decodeMimeString($this->header->from[0]->personal) : $this->header->from[0]->mailbox.'@'.$this->header->from[0]->host);
			
			if(@$this->header->cc)
				$this->mail['cc'] = $this->parseHeaderAddr($this->header->cc);
			
			if(@$this->header->sender)		
				$this->mail['senderAddr'] = $this->parseHeaderAddr($this->header->sender);
			
			if(@$this->header->reply_to)
				$this->mail['reply_toAddr'] = $this->parseHeaderAddr($this->header->reply_to);
		
			$this->mail['subject'] = $this->decodeMimeString(@$this->header->subject);
			$this->mail['udate'] = $this->header->udate;
			$this->mail['recent'] = $this->header->Recent;
			$this->mail['unseen'] = $this->header->Unseen;
			$this->mail['flagged'] = $this->header->Flagged;
			$this->mail['answered'] = $this->header->Answered;
			$this->mail['deleted'] = $this->header->Deleted;
			$this->mail['draft'] = $this->header->Draft;
			$this->mail['size'] = $this->header->Size;
			
			return 1;
			
		}
		
		public function parseHeader2() {
		
			$this->header = $this->mbox->getHeader($this->uid);
			$this->struct = $this->mbox->getStruct($this->uid);
			//Misc::pre($this->struct);
			
			@$this->mail["uid"] = $this->uid;
			@$this->mail['Msgno'] = $this->header->Msgno;
			
			@$this->mail['toaddress'] = (isset($this->header->toaddress)) ? $this->header->toaddress : '';
			@$this->mail['to'] = $this->parseHeaderAddr2($this->header->to);
			
			@$this->mail['fromaddress'] = (isset($this->header->fromaddress)) ? $this->header->fromaddress : '';
			@$this->mail['from'] = $this->parseHeaderAddr2($this->header->from);
			@$this->mail['fromaddress_sort'] = $this->parseHeaderAddr3($this->header->from);
			
			@$this->mail['ccaddress'] =  (isset($this->header->ccaddress)) ? $this->header->ccaddress : '';
			@$this->mail['cc'] = $this->parseHeaderAddr2($this->header->cc);
			
			@$this->mail['bccaddress'] =  (isset($this->header->bccaddress)) ? $this->header->bccaddress : '';
			@$this->mail['bcc'] = $this->parseHeaderAddr2($this->header->bcc);	
			
			@$this->mail['reply_toaddress'] =  (isset($this->header->reply_toaddress)) ? $this->header->reply_toaddress : '';
			@$this->mail['reply_to'] = $this->parseHeaderAddr2($this->header->reply_to);
			
			@$this->mail['senderaddress'] =  (isset($this->header->senderaddress)) ? $this->header->senderaddress : '';
			@$this->mail['sender'] = $this->parseHeaderAddr2($this->header->sender);
			
			@$this->mail['return_pathaddress'] =  (isset($this->header->returnaddress)) ? $this->header->returnaddress : '';
			@$this->mail['return_path'] = $this->parseHeaderAddr2($this->header->return_path);
			
			@$this->mail['remail'] =  (isset($this->header->remail)) ? $this->header->remail : '';
			@$this->mail['date'] =  (isset($this->header->date)) ? $this->header->date : '';
			@$this->mail['subject'] =  (isset($this->header->subject)) ? $this->header->subject : '';
			@$this->mail['in_reply_to'] =  (isset($this->header->in_reply_to)) ? $this->header->in_reply_to : '';
			@$this->mail['message_id'] =  (isset($this->header->message_id)) ? $this->header->message_id : '';
			@$this->mail['newsgroups'] =  (isset($this->header->newsgroups)) ? $this->header->newsgroups : '';
			@$this->mail['followup_to'] =  (isset($this->header->followup_to)) ? $this->header->followup_to : '';
			@$this->mail['references'] =  (isset($this->header->references)) ? $this->header->references : '';
			
			@$this->mail['Recent'] =  (isset($this->header->Recent)) ? $this->header->Recent : '';
			@$this->mail['Unseen'] =  (isset($this->header->Unseen)) ? $this->header->Unseen : '';
			@$this->mail['Flagged'] =  (isset($this->header->Flagged)) ? $this->header->Flagged : '';
			@$this->mail['Answered'] =  (isset($this->header->Answered)) ? $this->header->Answered : '';
			@$this->mail['Deleted'] =  (isset($this->header->Deleted)) ? $this->header->Deleted : '';
			@$this->mail['Draft'] =  (isset($this->header->Draft)) ? $this->header->Draft : '';
			
			@$this->mail['MailDate'] =  (isset($this->header->MailDate)) ? $this->header->MailDate : '';
			@$this->mail['Size'] =  (isset($this->header->Size)) ? $this->header->Size : '';
			@$this->mail['udate'] =  (isset($this->header->udate)) ? $this->header->udate : '';
			
			return 1;
			
		}
		
		public function parseStruct($aid) {
			
			if(@$this->struct->parts) {
			
				$flattenedParts = $this->flattenParts($this->struct->parts);
			
				foreach($flattenedParts as $partNumber => $part) {
				
					if($part->ifdisposition && ($part->disposition == 'attachment' || $part->disposition == 'ATTACHMENT') ) { // $part is an attachment
					
						$filename = $this->getFilenameFromPart($part);
						$key = count($this->mail['attachments']);
						$this->mail['attachments'][$key]['filename'] =  Misc::shortenStr(stripslashes($this->decodeMimeString($filename)), 55);
						$this->mail['attachments'][$key]['part'] = $partNumber;
						$this->mail['attachments'][$key]['encoding'] = $part->encoding;
						
					} else { // $part is no attachment
					
						$text_types = array('HTML', 'PLAIN', 'ALTERNATIVE');
					
						if(!in_array($part->subtype, $text_types)) {
							
							$filename = $this->getFilenameFromPart($part);
							$this->mail['inline_attachments'][$part->id]['filename'] =  Misc::shortenStr(stripslashes($this->decodeMimeString($filename)), 55);	
							$this->mail['inline_attachments'][$part->id]['part'] = $partNumber;
							$this->mail['inline_attachments'][$part->id]['encoding'] = $part->encoding;
						
						} else {
						
							$key = count(@$content);
							$content[$key]['type'] = $part->subtype;
							$content[$key]['encoding'] = $part->encoding;
							$content[$key]['part'] = $partNumber;
							
						}
						
					}
					
				}
				
			} else {
				
				$content[0]['type'] = $this->struct->subtype;
				$content[0]['encoding'] = $this->struct->encoding;
				$content[0]['part'] = 1;
				
			}
			
			foreach($content as $item) {
			
				if($item['type'] == 'PLAIN') {
				
					$this->mail['contentType'] = 'PLAIN';
					$this->mail['contentPart'] = $item['part'];
					$this->mail['contentEncoding'] = $item['encoding'];
					
					$this->mail['content'] = nl2br($this->mbox->getPart($this->uid, $item['part'], $item['encoding']));
					
				}elseif($item['type'] == 'HTML') {
				
					$this->mail['contentType'] = 'HTML';
					$this->mail['contentPart'] = $item['part'];
					$this->mail['contentEncoding'] = $item['encoding'];
					$this->mail['content'] = $this->mbox->getPart($this->uid, $item['part'], $item['encoding']);
					
				} else {
				
				
					$this->mail['contentType'] = 'HTML';
					$this->mail['contentPart'] = $item['part'];
					$this->mail['contentEncoding'] = $item['encoding'];
					$this->mail['content'] = $this->mbox->getPart($this->uid, $item['part'], $item['encoding']);
					
				}
				
			}
			
			// Replacing src of Inline Attachments
			
			preg_match_all('/src="cid:(.*)"/Uims', $this->mail['content'], $matches);
			
			if(count($matches)) {
	
				$search = array();
				$replace = array();
	
				foreach($matches[1] as $match) {
					$uniqueFilename = "A UNIQUE_FILENAME.extension";
					
					//file_put_contents("/path/to/images/$uniqueFilename", $emailMessage->attachments[$match]['data']);
					$search[] = "src=\"cid:$match\"";
					$replace[] = 'src="index.php?module=mails&page=download_attachment&aid='.$aid.'&uid='.$this->uid.'&part='.$this->mail['inline_attachments']['<'.$match.'>']['part'].'&encoding='.$this->mail['inline_attachments']['<'.$match.'>']['encoding'].'"';
				}
	
				$this->mail['content'] = str_replace($search, $replace, $this->mail['content']);
	
			}
			
		
			return 1;
			
		}
		
		public function parseStruct2() {
		
			$this->mail['source']['type'] = $this->struct->subtype;
			$this->mail['source']['encoding'] = $this->struct->encoding;
			$this->mail['source']['header'] = $this->mbox->getBody($this->uid, 0, FT_PEEK);
			$this->mail['source']['content'] = $this->mbox->getBody($this->uid, 1, FT_PEEK);
			
			
			$this->mail['attachments'] = array();
			$this->mail['inline_attachments'] = array();
			$this->mail['messages'] = array();
			
			@$flattenedParts = $this->flattenParts($this->struct->parts);
			
			foreach($flattenedParts as $partNumber => $part) {
			
				if($part->ifdisposition && ($part->disposition == 'attachment' || $part->disposition == 'ATTACHMENT') ) { // $part is an attachment
				
					$filename = $this->getFilenameFromPart($part);
					$key = count($this->mail['attachments']);
					$this->mail['attachments'][$key]['filename'] = $this->decodeMimeString($filename);
					$this->mail['attachments'][$key]['encoding'] = $part->encoding;
					$this->mail['attachments'][$key]['content'] = $this->mbox->getBody($this->uid, $partNumber, FT_PEEK);
					
				} else { // $part is no attachment
				
					$text_types = array('HTML', 'PLAIN', 'ALTERNATIVE');
				
					if(!in_array($part->subtype, $text_types)) {
						
						//
						// $PART->ID MANCHMAL NICHT VERFÜGBAR fixen
						//
						//
						$filename = $this->getFilenameFromPart($part);
						$this->mail['inline_attachments'][$part->id]['filename'] =  $this->decodeMimeString($filename);	
						$this->mail['inline_attachments'][$part->id]['encoding'] = $part->encoding;
						$this->mail['inline_attachments'][$part->id]['content'] = $this->mbox->getBody($this->uid, $partNumber, FT_PEEK);
					
					} else {
					
						$key = count($this->mail['messages']);
						$this->mail['messages'][$key]['type'] = $part->subtype;
						$this->mail['messages'][$key]['encoding'] = $part->encoding;
						$this->mail['messages'][$key]['content'] = $this->mbox->getBody($this->uid, $partNumber, FT_PEEK);
						
					}
					
				}
				
			}
		
			return 1;
			
		}
		
		public static function stripHtmlContainer($str) {
		
			$str = preg_replace("/<!DOCTYPE.*>/i","", $str); 	
			$str = preg_replace("/<html.*>/i","", $str); 	
			$str = preg_replace("/<\/html.*>/i","", $str); 	
			$str = preg_replace("/<head.*>/i","", $str); 
			$str = preg_replace("/<\/head.*>/i","", $str); 	
			$str = preg_replace("/<body.*>/i","", $str);
			$str = preg_replace("/<\/body.*>/i","", $str);
			
			return trim($str);  
		}
		
		
		public function modifyForReply() {
			
			$this->mail['subject'] = 'Re: '.$this->mail['subject'];
			
			//Misc::pre($this->mail);
			$this->mail['content'] = Mail::stripHtmlContainer($this->mail['content']);
			$this->mail['content'] = '<p>On '.date("d.m.Y – H:i", $this->mail['udate']).', '.$this->mail['fromAddr_former']." wrote:</p><p>".$this->mail['content'].'</p>';	
			$this->mail['content'] = '<br><table style="width: 100%; padding: 0; margin: 0;"><tr style="padding: 0; margin: 0;"><td style="width: 2px; background: #8eb1dd; padding: 0; margin: 0;"></td><td style="padding: 0 0 0 .5em; margin: 0">'.$this->mail['content'].'</td></tr></table>';
			
		//	die();
			
			/*if($this->mail['contentType'] == 'PLAIN') {
				$content = explode("\n", $this->mail['content']);
				foreach($content as $key => $item) 
					$content[$key] = '> '.$item."\n";
				
				$this->mail['content'] = implode($content);
				$this->mail['content'] = nl2br('On '.date("d.m.Y – H:i", $this->mail['udate']).', '.$this->mail['fromAddr_former']." wrote:\n".$this->mail['content']);
			} else {
				$this->mail['content'] = nl2br('On '.date("d.m.Y – H:i", $this->mail['udate']).', '.$this->mail['fromAddr_former']." wrote:\n").$this->mail['content'];	
				$this->mail['content'] = Mail::stripHtmlContainer($this->mail['content']);	 		
			}
				
			//Misc::pre($this->mail);*/
			
		}
		
		public function parseFromOptions($arr) {
			
			if($arr === NULL) 
				die('Software Error: Missing paramater $arr in Mail::parseFromOptions()');
				
			$toAddr = array();
			if(@$this->header->to)
				foreach($this->header->to as $key => $item)
					if(@$item->mailbox && @$item->mailbox != '' && @$item->host && @$item->host != '') $toAddr[] = $item->mailbox.'@'.$item->host;
			
			foreach($arr as $key => $item)
				$this->mail['fromOptions'] .= '<option value="'.$item.' &lt;'.$key.'&gt;" '.((in_array($key, $toAddr)) ? 'selected="selected"' : '').' >'.$item.' &lt;'.$key.'&gt;</option>';
		
		}
		
		static function validateAdressString($str) {
		
			$ar = explode(",", $str);
			$list = array();
			
			foreach($ar as $item) {
			
			 	$item = trim($item);
			 	
				if($item != '') {
				
					if(!filter_var($item, FILTER_VALIDATE_EMAIL)) {
					
						$chars = str_split($item);
						$max = strlen($item) - 1;
						$i = 0;
						$start = 0;
						$end = 0;
						while($chars[$i] != '<' && $i < $max) $i++;
						$start = $i+1;
						while($chars[$i] != '>' && $i < $max) $i++;
						if($chars[$i] != '>') return 0;
						$end = $i;
						$addr = substr($item, $start, $end-$start);	
						if(!filter_var($addr, FILTER_VALIDATE_EMAIL)) return 0;
						$name = substr($item, 0, $start-1);
						if(trim($name) != '') $list[$addr] = $name;
						else $list[] = $addr;
							
					} else $list[] = $item;
						
				}
				
			} return $list;
			
		}
	
		static function validateAdressStringReturn($str) {
	
			$ar = explode(",", $str);
			if(count($ar) > 1) return -1;
			$list = array();
		
			foreach($ar as $item) {
		
			 	$item = trim($item);
			 	
				if($item != '') {
			
					if(!filter_var($item, FILTER_VALIDATE_EMAIL)) {
				
						$chars = str_split($item);
						$max = strlen($item) - 1;
						$i = 0;
						$start = 0;
						$end = 0;
						while($chars[$i] != '<' && $i < $max) $i++;
						$start = $i+1;
						while($chars[$i] != '>' && $i < $max) $i++;
						if($chars[$i] != '>') return 0;
						$end = $i;
						$addr = substr($item, $start, $end-$start);	
						if(!filter_var($addr, FILTER_VALIDATE_EMAIL)) return 0;
						$list[] = $addr;
					
					} else $list[] = $item;
					
				}
			
			} return $list;
		
		}
	
		static function sendAsPlain($string) {
	
			$matches = array();
			$string = Mail::stripHtmlContainer($string);
			preg_match_all('/<[^\/](.*?)>/', $string, $matches);	
		
			foreach($matches[0] as $key => $item) {
		
				$item = trim(str_replace('>', '', str_replace('<', '', $item)));
				if($item != 'br' && $item != 'br /' && $item != 'br/' && $item != 'p') {
					return 0;
				}
				
			} return 1;
		
		}
	
		static function br2nl($str) {
	
			$str = str_replace('</p>', "\n", $str);
			$str = str_replace('<br />', "\n", $str);
			$str = str_replace('<br>', "\n", $str);
			return str_replace('<br/>', "\n", $str);
		
		}
		
		static function decodePart( $str, $encoding ) {
			
			switch ($encoding) {
		
				case 3: return utf8_encode(imap_base64($str));
				case 4: return utf8_encode(imap_qprint($str));
				default: return utf8_encode($str);
			
			}
		
		}
	
	} // <!-- end class ’Mail’ -->
	
?>
