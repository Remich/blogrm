$(document).ready(function() {
	
	$(document).on("click", "input[type=submit]", function(e) {
		
		$('#fileform').submit();
		startLoading();
	});
	

});

var startLoading = function(target) {
	
	loading = 1;
	$(target).css('opacity', '0.4');
	$('html').css('cursor', 'wait');
	$('body *').css('cursor', 'wait');
	$('body input').attr('disabled', 'true');
	
};

var stopLoading = function(target) {

	loading = 0;
	$(target).css('opacity', '1');
	$('html').css('cursor', 'default');
	$('body *').css('cursor', 'default');
	$('body input').removeAttr('disabled');
	
};


function stopUpload(result){
	
	if(result == 0)
		alert('Error' + $('#upload_target').contents().find('#error').html()); //jAlert($('#upload_target').contents().find('#error').html(), 'Error');
	
	
	if(result == 1) {
	
		var i = 0;
		$('#attachments').append('<span></span>');

		var names = new Array();
			$('#upload_target').contents().find('.names').each(function(){
			names.push($(this).html());
		});

		$('#upload_target').contents().find('.names_short').each(function( index ){
			$('#attachments span').append('<a href="">'+$(this).html()+'</a><input type="hidden" value="'+names[index]+'">');
		});

		var hashes = new Array();
		$('#upload_target').contents().find('.hashes').each(function(){
			hashes.push($(this).html());
		});

		$('#attachments span').find('a').each(function( index ){
			$(this).attr('id', hashes[index]);
			$(this).attr('href', 'ajax.php?action=GetUploadedFile&hash='+hashes[index]+'&name='+names[index]);
		});
		
		$('#attachments span').find('input').each(function( index ){
			$(this).attr('name', hashes[index]);
		});

		var str = $('#attachments span').html();
		$('#attachments span').remove();
		$('#attachments').append(str);

		var attach_files = $("#attach_files");
		attach_files.replaceWith( attach_files = attach_files.clone( true ) );
		
	}
	
	stopLoading('#attach_files');
	$('#attach_files input[type=submit]').removeAttr("disabled");
	
}