$(document).ready(function() {
	$('body').on('click', '#check_all input[type=checkbox]', function(e) {
		
		$(this).prop("checked", !$(this).prop("checked"));
		$('#bookmarks_table input[type=checkbox]').each(function() {
			$(this).prop("checked", !$(this).prop("checked") );
		});
	
	});
});
