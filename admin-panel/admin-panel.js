$(document).ready(function() {

	$.ajax({
        url : "admin-panel/index.php",
        dataType: "text",
        success : function (data) {
            $("#plugable_content").prepend(data);
        }
    });    
	

	// Apply padding to body
	padding = $("body").css('padding-top').split("");
	padding.pop();
	padding.pop();
	padding = parseInt(padding.join(""));

	$("body").css('padding-top', padding + 36 + 'px');



	// submit login form
	$('body').on('submit', '.login form', function(e) {
	
		e.preventDefault();
		var user = $('#username').val().trim();
		var pass = $('#password').val().trim();
				
		if (user === '' || pass === '') {
			alert('Please fill out the required fields', 'Error');
			return false;
		}

		
		$('#hidden').load('admin-panel/index.php?page=login_step_2&username=' + encodeURIComponent(user) + '&password=' + encodeURIComponent(CryptoJS.SHA256(pass)), function( result ) {
				
			if(result === "1") 
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
	
	
