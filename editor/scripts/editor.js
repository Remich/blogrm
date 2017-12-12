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
	            $("#pluggable_content").append(data);
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

	/**
	 * Global Variables & Objects
	 */  	

	// are we editing something currently? yes, then this points to the element
	var cur_editing = null;

	// is html mode active?
	var status_html = false;

	/**
	 * Edit History Object – Mix of Array and Linear List
	 * Used to keep track of changes made to the content of the page, for later saving
	 */  	
	var history = (function() {

		var that = {};
		that.items = new Array();

		that.getLength = function() {
			return that.items.length;
		}

		that.pop = function() {
			return that.items.pop();
		}

		that.push = function(value) {
			var id = value.attr("id");
			if (!that.find(id)) {
				that.items.push(value);
			}
		}

		that.remove = function(id) {
			for(var a = 0; a < that.items.length; a++) {
				if( that.items[a].attr("id") === id ) {
					that.items.splice(a--, 1);
				}
			}
			return true;
		}

		that.find = function(id) {
			for(var a = 0; a < that.items.length; a++) {
				if( that.items[a].attr("id") === id ) {
					return true;
				}
			}
			return false;
		}

		return that;
	}());	


	/**
	 * Initialize Buttons
	 */  

	// Array of Buttons
	var buttons = [];

	/**
	 * Button New File
	 */  
	var btn 			= {};
	btn.id 				= "newfile"
	btn.html_disabled 	= false;
	btn.onClick 		= function() {

		var parent = findparent(editing);
		var model = parent.attr("model");
		
		$("#hidden").load('ajax.php?model=' + encodeURIComponent(model) + 
				'&action=newfile', function ( bool ) {

			var item = $(bool);
			history.push(item);

			// TODO: generalize for other models
			$('#ListOfArticles').prepend( item );
		});
		
		$('#pp_editor').slideUp();
		if(editing != null) 
			editing.addClass('editable').removeAttr("contenteditable");
	};
	buttons.push(btn);

	/**
	 * Button Save File
	 */  


	/**
	 * Add Eventlisteners
	 */  
	for(var i in buttons) {
		var handle = buttons[i];
		$(document).on("click", "#"+handle.id, handle.onClick);
	}

});
