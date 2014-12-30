<p id="result"></p>
<form id="fileform" action="plugin.php?plugin_name=editor&page=file_upload" method="post" enctype="multipart/form-data" target="upload_target" >
	File: <input name="myfile[]" type="file" multiple />
	      <input type="submit" name="submitBtn" value="Upload" />
</form>
<iframe id="upload_target" name="upload_target" src="" style="width: 100%; height: 40:em; solid #fff;"></iframe>                 
