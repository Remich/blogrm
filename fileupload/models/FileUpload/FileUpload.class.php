<?php

	/**
	 * @file
	 * Copyright 2010-2014 René Michalke.
 	 */
	
	require_once('interfaces/iDBContentStatic.interface.php');
    require_once("models/ModelSingle/ModelSingle.class.php");
	
	class FileUpload extends ModelSingle {
		
		private $_dir = null;
		protected $_name = "FileUpload";
		protected $_templateDir = "fileupload/models/";
		
		/**
		* Constructor
		*
		*/
		function __construct() {
			$this->_dir = "upload/";
		} 
		
		private function error($msg) {
			die('<div id="error">'.$msg.'</div><script language="javascript" type="text/javascript">parent.stopUpload(0);</script>');
		}
				
		
		function UploadFiles($files) {
		
			$max_filesize = ini_get('upload_max_filesize');
			$max_filesize_b = returnBytes($max_filesize);
			$result = 1;
				
			if(!$files)
				$this->error('Error. Maximum filesize of '.$max_filesize_b.' Bytes ('.$max_filesize.'B) for at least one of the selected files exceeded.');
				
			if(count($files['myfile']) == 0)
				$this->error('The overall filesize of the selected files exceeds maximum of '.ini_get('post_max_size').'B. Try multiple uploads with fewer selected files.');
		
			// check error flags
			foreach($files['myfile']['error'] as $item) {
					
				if($item == 1)
					$this->error('Maximum filesize of '.$max_filesize_b.' Bytes ('.$max_filesize.'B) for at least one of the selected files exceeded.');
					
				if($item == 4)
					$this->error('No files selected');
					
			}
		
			// check if uploaded file is a real uploaded file
			foreach($files['myfile']['tmp_name'] as $item)
				if(!is_uploaded_file($item))
					$this->error('Something is wrong with the file you are trying to upload.');
		
			// check filename length
			foreach($files['myfile']['name'] as $item)
				if((mb_strlen($item,"UTF-8") > 255))
					$this->error('Filename exceeds maximum length of 255 characters');
					
				// check filesize
				foreach($files['myfile']['size'] as $item)
					if($item > $max_filesize_b)
						$this->error('Maximum filesize of '.$max_filesize_b.' Bytes ('.$max_filesize.'B) for at least one of the selected files exceeded.');
						
					// hash filename
					foreach($files['myfile']['name'] as $item)
						$files['myfile']['hash'][] = hash("sha256", $item.microtime().time());
	
					foreach($files['myfile']['name'] as $item)
						$files['myfile']['name_short'][] = shortenStr($item, 55);
	
	
					// move file to desired location
					$destination_path = getcwd().DIRECTORY_SEPARATOR.$this->_dir;
						
					foreach($files['myfile']['tmp_name'] as $key => $item) {
							
						$target_path = $destination_path.basename( $files['myfile']['name'][0]);
						if(!move_uploaded_file($item, $target_path))
							$this->error('A software error occured. Please contact the admin');
							
					}
					echo "Dateien erfolgreich hochgeladen.";
						
					/*foreach($files['myfile']['name'] as $item)
						echo '<div class="names">'.$item.'</div>';
	
					foreach($files['myfile']['name_short'] as $item)
						echo '<div class="names_short">'.$item.'</div>';
	
					foreach($files['myfile']['hash'] as $item)
						echo '<div class="hashes">'.$item.'</div>';*/
	
						
					sleep(3);
					die('<script language="javascript" type="text/javascript">parent.stopUpload(1);</script>');
		}
		
		// TODO: Modify
		public function GetUploadedFile() {
				
			$this->isInRequest(array('hash', 'name'));
				
			header("Content-Description: File Transfer");
			header("Content-Type: application/octet-stream");
			header("Content-Disposition: attachment; filename=".$this->request['name']);
			header("Content-Transfer-Encoding: binary");
			header("Expires: 0");
			header("Cache-Control: must-revalidate");
			header("Pragma: public");
				
			$path = getcwd().DIRECTORY_SEPARATOR.'modules/mails/upload/'.DIRECTORY_SEPARATOR;
			die(readfile($path.$this->request['hash']));
				
		}

	} // <!-- end class ’Controller’ -->
?>
