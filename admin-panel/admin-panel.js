$(document).ready(function() {

	/**
	 * Init Function
	 */  
	(function Init() {

		// Move the actual page-content down, otherwise some of it will be below the panels

		window.initial_body_padding = parseInt( $("body").css('padding-top') ); // parseInt removes the „px“
		window.setPaddingTopBody = function() {
			pluggable_content_height = $("#pluggable_content").height();
			$("body").css('padding-top', pluggable_content_height + window.initial_body_padding + 'px');
		};

		$(window).on('load', function() {
            window.setPaddingTopBody();
		});

		// Load and inject the HTML
		$.ajax({
	        url : "admin-panel/index.php",
	        dataType: "text",
	        success : function (data) {
	            $("#pluggable_content").prepend(data);
	            window.setPaddingTopBody();
	        }
	    });    

		
	})();


	// submit login form
	$('body').on('submit', '.login form', function(e) {
	
		e.preventDefault();
		var user = $('#username').val().trim();
		var pass = $('#password').val().trim();
				
		if (user === '' || pass === '') {
			alert('Please fill out the required fields', 'Error');
			return false;
		}

		
		// TODO test for security: e.g. call manually with empty strings etc.…
		$('#hidden').load('admin-panel/index.php?page=login_step_2&username=' + encodeURIComponent(user) + '&password=' + encodeURIComponent(CryptoJS.SHA256(pass).toString()), function( result ) {
				
			if(result.trim() === "1") 
				window.location.reload(true);
			else
				alert(result, 'Error');
				
		});

	}); 


	// cancel login progress
	$('body').on('reset', '.login form', function(e) {
		e.preventDefault();
		window.location.href = "toggle.php?item=admin-panel";	
	});
	
});
	
	
