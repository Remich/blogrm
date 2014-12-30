<?php

$this->_footer .= '<script type="text/javascript" src="libs/jstree/jstree.js"></script>';
$this->_header .= '<link rel="stylesheet" href="libs/jstree/themes/default/style.min.css" />';
$this->_footer .= '<script type="text/javascript" src="fileupload/fileupload.js"></script>';

require_once("fileupload/models/Folder/Folder.class.php");

$folder = new Folder("upload");
$folder->load();
$this->_view->assign('Folder', $folder->display() );


require_once("fileupload/models/FileUpload/FileUpload.class.php");

$upload = new FileUpload();
if(sizeof($this->_request['files']) == 0) {
	$this->_view->assign('FileUpload', $upload->display() );
} else {
	$upload->UploadFiles($this->_request['files']);
}

$this->_view->setTemplateDir('fileupload/views/');
$this->_view->setTemplate('index');
	
?>
