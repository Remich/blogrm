$(document).ready(function() {

	/* TODO: remove onclick="" in editor.html */
	/* BUGS: 
	 *	1!!!!: when clicking a button (bold), a  blur event is fired, and then the click event.
	 *			however, the  watchButtonInterval() might run before the click event restores the focus, thus the action of the button is not executed!!!
	 *			this sucks.!
	 * 
	 * 

	
	/**
	 * Global Variables & Objects:
	 */  	

	// is html mode active?
	var status_html = false;

	/* are we editing something [0] currently? yes, then this points to the element

	 * [0] - The following dom-elements are compatible with the editor

	 * 	1. a element with the following attributes:
	 *		- id="[0-9]*" 	/* Regular Expression (ERE-Syntax) between the "", also below
	 		- model="[a-z0-9]" 
	 	2. the same element as 1. or a child element of 1. with the following properties:
			- model_key="" // TODO find type
			- class="editable"
			- the values/content of elements with attribute *model_key=""* AND *class="editable"*
	 		  . . .
	 */
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

		// TODO Make non-recursive
		that.delete_item = function(to_delete) {


			item = to_delete.pop();

			var model = item.attr("model");
			var id = item.attr("id");

			if( confirm("Really Delete Object?") ) {
				$("#hidden").load('ajax.php?model=' + encodeURIComponent(model) + 
						'&action=delete&id=' + encodeURIComponent(id), function ( bool ) {
					
						if (bool.trim() === "#t") {

							$("#"+id).slideUp("slow", function() {
								this.remove();
							});

							// remove model from edit edit_log,
							// otherwise it will reappear,
							// when saving any other object
							if( edit_log.remove(id, model) ) {
								if(to_delete.length > 0) {
									delete_item(to_delete);
								}
							}

						}

				});
			}		
		
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
	 * Init Function: Initialize the editor
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

	/**
	 * toggles a class
	 *
	 * @param      {jQuery-DOM}  el         The jQuery-DOM 
	 * @param      {string}  	 className  The class name
	 */
	var toggleClass = function(el, className) {
		if(el.hasClass(className) === true) {
			el.removeClass(className);
		} else {
			el.addClass(className);
		}
	}

	/**
	 * inverts a boolean variable
	 *
	 * @param      {boolean}  sw      The variable to invert
	 * @return     {boolean}  The inverted boolean value
	 */
	var invertBool = function(sw) {
		if(sw === true) {
			return false;
		} else {
			return true;
		}
	};

	/**
	 * todo
	 *
	 * @param      {string}  str     The string
	 * @return     {string}  { description_of_the_return_value }
	 */
	function rgbToHex(str) {

		function componentToHex(c) { var hex = c.toString(16);
			return hex.length == 1 ? "0" + hex : hex;
		}

		str = str.replace("rgb(", "").replace(")", "").replace(/ /g, "");
		str = str.split(",");
		return "#" + componentToHex(parseInt(str[0])) + componentToHex(parseInt(str[1])) + componentToHex(parseInt(str[2]));
	}

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
		if(cur_editing != null && e.relatedTarget == null) {	/* blur by clicking anywhere in the document, except a button */
			removeFocus(cur_editing);
			cur_editing = null;
		} else {	/* blur, when a button was pressed */
			newFocus(prev_editing);
		}
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
	 * Button Delete File
	 */
	var btn 			= {};
	btn.id 				= "deletefile"
	btn.html_disabled 	= false;
	btn.isButtonModeActive = false;
	btn.checkBoxClickHandler = null;
	btn.onClick 		= function() {

		var toggleCheckboxes = function() {

			var clickHandler = function() {
				console.log("jojaosdjfoasf");
			};

			var checkBox = function(fade) {

				if(fade === 'fade-in') {
					if( $(".pcheckbox").length > 0) {
						$(".pcheckbox").fadeIn();
					} else {
						$("article").before('<input type="checkbox" name="deleteArticle" value="" class="pcheckbox">');	
					}

					$(".pcheckbox").change(function() {
						console.log("changed");
					});
				} else if(fade === 'fade-out') {
					$(".pcheckbox").fadeOut();	
				}

			};

			if(btn.isButtonModeActive === true) {
				console.log("checkbox fade-out");
				checkBox("fade-out");
			} else {
				console.log("checkbox fade-in");
				checkBox("fade-in");
			}
		};

		var toggleConfirm = function() {

			if(btn.isButtonModeActive === true) {
				console.log("confirmbox fade-out");
			} else {
				console.log("confirmbox fade-in");
			}
		};

		toggleClass($(this), "mode_active");
		toggleCheckboxes();
		toggleConfirm();
		btn.isButtonModeActive = invertBool(btn.isButtonModeActive);
	};
	btn.isEnabled = function() {
		return true;
	};
	buttons.push(btn);

	/**
	 * Button Cancel
	 */
	var btn 			= {};
	btn.id 				= "cancel"
	btn.html_disabled 	= false;
	btn.isButtonModeActive = false;
	btn.checkBoxClickHandler = null;
	btn.onClick 		= function() {
		$('#pp_editor').slideUp();
		if(cur_editing != null) {
			removeFocus(cur_editing);
			cur_editing = null;
		}
	};
	btn.isEnabled = function() {
		return true;
	};
	buttons.push(btn);

	/**
	 * Button Undo
	 */
	var btn 			= {};
	btn.id 				= "undo"
	btn.html_disabled 	= true;
	btn.isButtonModeActive = false;
	btn.checkBoxClickHandler = null;
	btn.onClick 		= function() {
		console.log("undo");
		document.execCommand('undo', false, null);
	};
	btn.isEnabled = function() {
		return document.queryCommandEnabled(this.id)
	};
	buttons.push(btn);

	/**
	 * Button Redo
	 */
	var btn 			= {};
	btn.id 				= "redo"
	btn.html_disabled 	= true;
	btn.isButtonModeActive = false;
	btn.checkBoxClickHandler = null;
	btn.onClick 		= function() {
		console.log("redo");
		document.execCommand('redo', false, null);
	};
	btn.isEnabled = function() {
		return document.queryCommandEnabled(this.id)
	};
	buttons.push(btn);

	/**
	 * Button Remove Format
	 */
	var btn 			= {};
	btn.id 				= "removeformat"
	btn.html_disabled 	= true;
	btn.isButtonModeActive = false;
	btn.checkBoxClickHandler = null;
	btn.onClick 		= function() {
		console.log("removeformat");
		document.execCommand('removeformat', false, null);
	};
	btn.isEnabled = function() {
		return document.queryCommandEnabled(this.id)
	};
	buttons.push(btn);


	/**
	 * Selection Font Name
	 */
	var btn 			= {};
	btn.id 				= "fontname"
	btn.html_disabled 	= true;
	btn.isButtonModeActive = false;
	btn.checkBoxClickHandler = null;
	btn.onClick 		= function() {
		console.log("fontname");
		return true;
	};
	btn.isEnabled = function() {
		// check also, if the font selection box has the correct value, depending on where the cursor is
		if(cur_editing != null) {
			var fontName = document.queryCommandValue("fontname");
			var fonts = fontName.split(",");
			$('#pp_editor #fontname').val(fonts[0]);
		}
		return document.queryCommandEnabled(this.id)
	};
	$(document).on("change", "#pp_editor #fontname", function(e) {
		document.execCommand("fontname", false, $(this).val() );
		var fontName = document.queryCommandValue("fontname");
		var fonts = fontName.split(",");
		$('#pp_editor fontname').val(fonts[0]);
	});
	buttons.push(btn);

	/**
	 * Selection Fontsize
	 */
	var btn 			= {};
	btn.id 				= "fontsize"
	btn.html_disabled 	= true;
	btn.isButtonModeActive = false;
	btn.checkBoxClickHandler = null;
	btn.onClick 		= function() {
		console.log("fontsize");
		return true;
	};
	btn.isEnabled = function() {
		// check also, if the size selection box has the correct value, depending on where the cursor is
		if(cur_editing != null) {
			var fontSize = document.queryCommandValue("fontsize");
			var size = fontSize.split(",");
			$('#pp_editor #fontsize').val(size[0]);
		}
		return document.queryCommandEnabled(this.id)
	};
	$(document).on("change", "#pp_editor #fontsize", function(e) {
		document.execCommand("fontsize", false, $(this).val() );
		var fontSize = document.queryCommandValue("fontsize");
		var size = fontSize.split(",");
		$('#pp_editor #fontsize').val(size[0]);
	});
	buttons.push(btn);


	// TODO implement
	/**
	 * Fore Color
	 */
	var btn 			= {};
	btn.id 				= "forecolor"
	btn.html_disabled 	= true;
	btn.isButtonModeActive = false;
	btn.checkBoxClickHandler = null;
	btn.onClick 		= function() {
		console.log("forecolor");
		return true;
	};
	btn.isEnabled = function() {
		return false;
		// return document.queryCommandEnabled(this.id)
	};
	$(document).on("change", "#pp_editor #forecolor", function(e) {
		// console.log("change forecolor");
		// // console.log("hi");
		// console.log($(this).val());
		// document.execCommand("ForeColor", false, $(this).val() );
		// newFocus(prev_editing);
		// var foreColor = document.queryCommandValue("ForeColor");
		// console.log("new: " + foreColor);



		// document.execCommand('styleWithCSS', false, true);
		// var foo = document.queryCommandState('styleWithCSS');

		// console.log($(this).val());
		 //    document.execCommand('foreColor', false, $(this).val());

		// var fontSize = document.queryCommandValue("forecolor");
		// var size = fontSize.split(",");
		// $('#pp_editor #forecolor').val(size[0]);
	});
	buttons.push(btn);

	// TODO implement
	/**
	 * Back Color
	 */
	var btn 			= {};
	btn.id 				= "backcolor"
	btn.html_disabled 	= true;
	btn.isButtonModeActive = false;
	btn.checkBoxClickHandler = null;
	btn.onClick 		= function() {
		console.log("backcolor");
		return true;
	};
	btn.isEnabled = function() {
		return false;
		// return document.queryCommandEnabled(this.id)
	};
	$(document).on("change", "#pp_editor #backcolor", function(e) {
		// console.log("change backcolor");
		// // console.log("hi");
		// console.log($(this).val());
		// document.execCommand("ForeColor", false, $(this).val() );
		// newFocus(prev_editing);
		// var foreColor = document.queryCommandValue("ForeColor");
		// console.log("new: " + foreColor);



		// document.execCommand('styleWithCSS', false, true);
		// var foo = document.queryCommandState('styleWithCSS');

		// console.log($(this).val());
		 //    document.execCommand('foreColor', false, $(this).val());

		// var fontSize = document.queryCommandValue("backcolor");
		// var size = fontSize.split(",");
		// $('#pp_editor #backcolor').val(size[0]);
	});
	buttons.push(btn);





	/**
	 * Button Bold
	 */
	var btn 			= {};
	btn.id 				= "bold"
	btn.html_disabled 	= true;
	btn.isButtonModeActive = false;
	btn.checkBoxClickHandler = null;
	btn.onClick 		= function(e) {
		console.log("bold");
		document.execCommand('bold', false, null);
	};
	btn.isEnabled = function() {
		return document.queryCommandEnabled(this.id)
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
