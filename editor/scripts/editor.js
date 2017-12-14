$(document).ready(function() {



	
	/**
	 * Global Variables & Objects:
	 */  	

	// is html mode active?
	var status_html = false;
	// are we editing something currently? yes, then this points to the element
	var cur_editing = null;
	// points to the same as cur_editing, however "cur_editing" might become null (e.g.: when blur event is fired), so we sometimes need to remember what "cur_editing" was before it became null
	var prev_editing = null;
	// used to check if window.beforeunload event has been cancelled
	var timeout;

	/**
	 * Edit-Log – Mix of Array and Linear List
	 * Used to keep track of changes made to the elements of the page, for later saving
	 */  	
	var edit_log = (function() {

		var that = {};
		that.items = new Array();

		that.getLength = function() {
			return that.items.length;
		};

		that.pop = function() {
			return that.items.pop();
		};

		that.push = function(value) {
			var id = value.attr("id");
			if (!that.find(id)) {
				that.items.push(value);
			}
		};

		that.remove = function(id) {
			for(var a = 0; a < that.items.length; a++) {
				if( that.items[a].attr("id") === id ) {
					that.items.splice(a--, 1);
				}
			}
			return true;
		};

		that.find = function(id) {
			for(var a = 0; a < that.items.length; a++) {
				if( that.items[a].attr("id") === id ) {
					return true;
				}
			}
			return false;
		};

		that.save = function() {

			// Nothing to save
			if(that.getLength() === 0)
				return;

			/*	
			 * finds and returns all the types of editable elements and its values of a particular entry (e.g. blog post #12)
			 */
			var findeditables = function(obj) {

				var editables = [];
				var children = obj.find("*");

				$.each(children, function(index, selector) {
					if($(selector).attr("model_key") !== undefined) {
						var child = {};
						child.key = $(selector).attr("model_key");
						child.val = $(selector).html();
						editables.push(child);
					}
				});

				return editables;
			};

			data = [];
			while(that.getLength() !== 0) {
				var item = that.pop();
				var tmp = { 
					"id"	: item.attr("id"),
					"model" : item.attr("model"),
					"data"	: findeditables(item)
				}
				data.push(tmp);
			}

			$("#hidden").load(
				'ajax.php?&action=save', 
				{ data : data }, 
				function ( ret ) {
					if (ret.trim() !== "#t") {
						alert('Fehler beim Speichern');
						console.log(ret);
					}
				}
			);
			
		};

		return that;
	}());	


	/**
	 * Init Function:
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

			timeout = setTimeout(function() {
		        addFocus(prev_editing);
		    }, 100);

			if(edit_log.getLength() > 0)
				return "You have attempted to leave this page.  If you have made any changes to the fields without clicking the Save button, your changes will be lost.  Are you sure you want to exit this page?";
		});

		$(window).on('unload', function() {
			clearTimeout(timeout);
		});

	})();




	/**
	 * Helper Functions:
	 */  


	/**
	 * Finds the parent element which is of tag-type article of a element
	 *
	 * @param      {Function}  obj     a jQuery object
	 * @return     {Function}  a jQuery object
	 */
	var findParent = function(obj) {
		var curr = obj;
		while(! curr.is("article") ) {
			curr = curr.parent();
		}
		return curr;
	}


	var removeFocus = function(elem) {
		if(elem != null)
			elem.addClass('editable').removeClass('editing').removeAttr("contenteditable");
	}
	var addFocus = function(elem) {
		elem.removeClass('editable').addClass('editing').attr("contenteditable", "true").focus();
		cur_editing = elem;
		prev_editing = cur_editing;
	}
	var newFocus = function(newElem) {
		removeFocus(cur_editing);
		addFocus(newElem);
	}

	var placeCaretAtEnd = function(el) {
	    el.focus();
	    if (typeof window.getSelection != "undefined"
	            && typeof document.createRange != "undefined") {
	        var range = document.createRange();
	        range.selectNodeContents(el);
	        range.collapse(false);
	        var sel = window.getSelection();
	        sel.removeAllRanges();
	        sel.addRange(range);
	    } else if (typeof document.body.createTextRange != "undefined") {
	        var textRange = document.body.createTextRange();
	        textRange.moveToElementText(el);
	        textRange.collapse(false);
	        textRange.select();
	    }
	};


	$(document).on("focus", "a.editable", function(e) {
		newFocus($(this));

		$('#pp_editor').slideDown(function() {
		});

		var parent = findParent($(this));
		edit_log.push(parent);
	});

	$(document).on("click", ".editable", function(e) {
		if($(this).is("span")) {
			placeCaretAtEnd( this );
		}

		newFocus($(this));

		$('#pp_editor').slideDown(function() {
		});

		var parent = findParent($(this));
		edit_log.push(parent);
	});

	$(document).on("blur", ".editing", function(e) {
		removeFocus($(this));
		cur_editing = null;
	});


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

		$("#hidden").load('ajax.php?model=Article&action=newfile', function ( res ) {
			var item = $(res);
			edit_log.push(item);

			$('#ListOfArticles').slideToggle(function() {
				$(this).prepend(item).slideToggle();
			});

		});
	};
	btn.isEnabled 		= function() { return true; };
	buttons.push(btn);

	/**
	 * Button Save File
	 */  
	var btn 			= {};
	btn.id 				= "save"
	btn.html_disabled 	= false;
	btn.onClick 		= function() {
		edit_log.save();	
		removeFocus(cur_editing);
	};
	btn.isEnabled 		= function() {
		if(edit_log.getLength() > 0)
			return true;
		else 
			return false;
	};
	buttons.push(btn);


	/**
	 * Add Eventlisteners
	 */  
	for(var i in buttons) {
		var handle = buttons[i];
		$(document).on("click", "#"+handle.id, handle.onClick);
	}

	/**
	 * enable/disable buttons depending on context
	 */  
	var disableButton = function(btnID) {
		if(!$('#pp_editor #'+ btnID).hasClass("not-supported"))
			$('#pp_editor #'+ btnID).addClass("not-supported").attr('disabled', 'true');
	};
	var enableButton = function(btnID) {
		if($('#pp_editor #'+ btnID).hasClass("not-supported"))
			$('#pp_editor #'+ btnID).removeClass("not-supported").removeAttr('disabled');
	};

	var watchButtonInterval = setInterval(function() {
		for(var i in buttons) {
			var handle = buttons[i];
			if(handle.isEnabled() === true) 
				enableButton(handle.id);
			else
				disableButton(handle.id);

		}
	}, 100);

});
