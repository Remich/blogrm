<!DOCTYPE html>
<html lang="en-US">
<head>
<meta http-equiv="Content-Type" content="text/html;" charset="UTF-8" />
<title>FileUpload</title>
		<?php echo $this->_['header']; ?>
	</head>
	<body>
		<section id="choose_link">
		<h1>New Link<h1></h1>
		<input type="button" value="New Link" onclick="document.execCommand('createlink', false, prompt('Enter a URL:', 'http://'))">	
		<h1>Choose from existing Files</h1>
		<?php echo $this->_['Folder']; ?>	
		</section>
		<br><br>
		<?php echo $this->_['footer']; ?>
	</body>  
</html>