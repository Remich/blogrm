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
	
});
	
	
