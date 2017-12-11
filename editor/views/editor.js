$(document).ready(function() {

	/**
	 * Init Function
	 */  
	(function Init() {

		// Load and inject the HTML
		$.ajax({
	        url : "editor/views/editor.html",
	        dataType: "text",
	        success : function (data) {
	            $("#pluggable_content").append(data); // TODO:  rename pluggable_content --> pluggable_content
	            window.setPaddingTopBody();
	        }
	    });    

		$("body").addClass("p_editor");
		
		// Set handler, which notifies of unsaved changes
		$(window).on('beforeunload', function() {
			if(history.getLength() > 0)
				return "You have attempted to leave this page.  If you have made any changes to the fields without clicking the Save button, your changes will be lost.  Are you sure you want to exit this page?";
		});

	})();

});
