$(document).ready(function() {
	
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
			that.items.push(value);
		}

		that.remove = function(id, model) {
			for(var a = 0; a < that.items.length; a++) {
				if( that.items[a].attr("model") === model &&
					that.items[a].attr("model_id") === id) {
					that.items.splice(a--, 1);
				}
			}
			return true;
		}

		that.find = function(id, model, key) {
			for(var a = 0; a < that.items.length; a++) {

				if( that.items[a].attr("model_id") === id
					&& that.items[a].attr("model") === model
					&& that.items[a].attr("model_key") === key) {

					return true;
				}
			}
			return false;
		}

		return that;

	}());	


	// var history = new Array();

	// ***
	// * Start Buttons
	// ***

	var editing = null;
	var html = false;
	
	var buttons = new Array();
	var q = buttons.length;
	buttons[q++] = { 
		id : 'newfile', 
		action : 'inserthtml',
		on_html_disabled : false 
	}; 
	buttons[q++] = { 
		id : 'save', 
		action : 'inserthtml',
		on_html_disabled : false 
	}; 
	/*buttons[c] = new Array();
	buttons[c].id = 'deletefile';
	buttons[c].action = 'inserthtml';
	buttons[c++].on_html_disabled = false;*/
	buttons[q++] = { 
		id : 'undo', 
		action : 'undo',
		on_html_disabled : true 
	}; 
	buttons[q++] = { 
		id : 'redo', 
		action : 'redo',
		on_html_disabled : true 
	}; 
	buttons[q++] = { 
		id : 'removeformat', 
		action : 'removeformat',
		on_html_disabled : true 
	}; 
	buttons[q++] = { 
		id : 'fontname', 
		action : 'fontname',
		on_html_disabled : true 
	}; 
	buttons[q++] = { 
		id : 'fontsize', 
		action : 'fontsize',
		on_html_disabled : true 
	}; 
	buttons[q++] = { 
		id : 'color_front', 
		action : 'color_front',
		on_html_disabled : false 
	}; 
	buttons[q++] = { 
		id : 'color_back', 
		action : 'color_back',
		on_html_disabled : false 
	}; 
	buttons[q++] = { 
		id : 'formatblock', 
		action : 'formatblock',
		on_html_disabled : true 
	}; 
	buttons[q++] = { 
		id : 'bold', 
		action : 'bold',
		on_html_disabled : true 
	}; 
	buttons[q++] = { 
		id : 'italic', 
		action : 'italic',
		on_html_disabled : true 
	}; 
	buttons[q++] = { 
		id : 'underline', 
		action : 'underline',
		on_html_disabled : true 
	}; 
	buttons[q++] = { 
		id : 'strikethrough', 
		action : 'strikethrough',
		on_html_disabled : true 
	}; 
	buttons[q++] = { 
		id : 'subscript', 
		action : 'subscript',
		on_html_disabled : true
	}; 
	buttons[q++] = { 
		id : 'superscript', 
		action : 'superscript',
		on_html_disabled : true
	}; 
	buttons[q++] = { 
		id : 'justifyleft', 
		action : 'justifyleft',
		on_html_disabled : true
	}; 
	buttons[q++] = { 
		id : 'justifycenter', 
		action : 'justifycenter',
		on_html_disabled : true
	}; 
	buttons[q++] = { 
		id : 'justifyright', 
		action : 'justifyright',
		on_html_disabled : true
	}; 
	buttons[q++] = { 
		id : 'justifyfull', 
		action : 'justifyfull',
		on_html_disabled : true
	}; 
	buttons[q++] = { 
		id : 'indent', 
		action : 'indent',
		on_html_disabled : true
	}; 
	buttons[q++] = { 
		id : 'outdent', 
		action : 'outdent',
		on_html_disabled : true
	}; 
	buttons[q++] = { 
		id : 'insertorderedlist', 
		action : 'insertorderedlist',
		on_html_disabled : true
	}; 
	buttons[q++] = { 
		id : 'inserunorderedlist', 
		action : 'inserunorderedlist',
		on_html_disabled : true
	}; 
	buttons[q++] = { 
		id : 'inserthorizontalruler', 
		action : 'inserthorizontalruler',
		on_html_disabled : true
	}; 
	buttons[q++] = { 
		id : 'blockquote', 
		action : 'formatblock',
		on_html_disabled : true
	}; 
	buttons[q++] = { 
		id : 'createlink', 
		action : 'createlink',
		on_html_disabled : true
	}; 
	buttons[q++] = { 
		id : 'unlink', 
		action : 'unlink',
		on_html_disabled : true
	}; 
	buttons[q++] = { 
		id : 'inserthtml', 
		action : 'inserthtml',
		on_html_disabled : true
	}; 
	buttons[q++] = { 
		id : 'insertimage', 
		action : 'insertimage',
		on_html_disabled : true
	}; 
	buttons[q++] = { 
		id : 'imageleft', 
		action : 'imageleft',
		on_html_disabled : true
	}; 
	buttons[q++] = { 
		id : 'imagenone', 
		action : 'imageleft',
		on_html_disabled : true
	}; 
	buttons[q++] = { 
		id : 'imageright', 
		action : 'imageright',
		on_html_disabled : true
	}; 
	buttons[q++] = { 
		id : 'edithtml', 
		action : 'edithtml',
		on_html_disabled : false
	}; 
	
	console.log(buttons);

	// ***
	// * End Buttons
	// ***
	
	
	//library editor
	function componentToHex(c) { var hex = c.toString(16);
		return hex.length == 1 ? "0" + hex : hex;
	}

	function rgbToHex(str) {
		str = str.replace("rgb(", "").replace(")", "").replace(/ /g, "");
		str = str.split(",");
		return "#" + componentToHex(parseInt(str[0])) + componentToHex(parseInt(str[1])) + componentToHex(parseInt(str[2]));
	}


	var save_items = function() {
		
		data = [];
		while(history.getLength() !== 0) {

			item = history.pop();

			var model = item.attr("model");
			var id = item.attr("model_id");
			var key = item.attr("model_key");
			var value = item.html();

			data.push({ "model" : model, "id" : id, "key" : key, "value" : value }) ;

		}

		$("#hidden").load('ajax.php?&action=save', 
				{ data : data }, function ( bool ) {
			
			if (bool.trim() !== "#t") {
				alert('Fehler beim Speichern');
				console.log(bool);
			}
		});
		
	}
	
	var delete_item = function(to_delete) {
		item = to_delete.pop();
		var model = item.attr("model");
		var id = item.attr("model_id");

		if( confirm("Really Delete Object?") ) {
			$("#hidden").load('ajax.php?model=' + encodeURIComponent(model) + 
					'&action=delete&id=' + encodeURIComponent(id), function ( bool ) {
				
					if (bool.trim() === "true") {

						$('#a'+id).slideUp("slow", function() {
							this.remove();
						});

						// remove model from edit history,
						// otherwise it will reappear,
						// when saving any other object
						if( history.remove(id, model) ) {
							if(to_delete.length > 0) {
								delete_item(to_delete);
							}
						}

					}

			});
		}	
		
	}
	
		
	
	$(document).on("click", "#insertimage", function(e) {
		$.colorbox({href:"plugin.php?plugin_name=editor&page=file_upload", width: "65%", height: "90%"});
		//alert('File Upload is disabled in Demo Mode');
	});
	
	$(document).on("click", "#imageleft", function(e) {
		
		document.execCommand('formatblock', false, 'pre');
		console.log(window.getSelection());
	});
	
	$(document).on("click", "#createlink", function(e) {
		$.colorbox({href:"plugin.php?plugin_name=editor&page=files_display", width: "65%", height: "90%"});
	});
	
	$(document).on("click", "#fileupload #uploaded_files *", function(e) {
		e.preventDefault();
		//alert('File Upload is disabled in Demo Mode');
		//return false;
		var html = '<img src="upload/'+($(this).attr('name'))+'">';
		document.execCommand('inserthtml', false, html);		
	});
	
	$(document).on("click", "#choose_link #uploaded_files *", function(e) {
		e.preventDefault();
		//alert('File Upload is disabled in Demo Mode');
		//return false;
		var url = $(this).attr('href');
		document.execCommand('createlink', false, url);
	});
	
	$(document).on("click", "#save", function(e) {
		
		save_items();	
		
		$('#pp_editor').slideUp();

		if(editing != null)
			editing.addClass('editable').removeAttr("contenteditable");
		
	});
	
	$(document).on("click", "#deletefile", function(e)  {
		
		var items = new Array();
		$(document).find("input[type=checkbox]").each(function() {
			console.log( $(this).prop("checked") );
			if($(this).prop("checked"))
				items.push($(this));
		});
		
		if(editing != 23 && editing != null && items.length==0) {	
			
			var item = new Array
			item.push(editing);
			delete_item(item);
			
		} else {
			if(items.length>0) {
				delete_item(items);
			}
			
		}
	});
	
	load_content = function(dom, id) {
		
		$("#hidden").load('ajax.php?id=' + encodeURIComponent(id) + 
				'&action=load_content', function ( bool ) {
			console.log(bool);
			$(dom).append(bool);
			 
		 })
		
	}
	
	$(document).on("click", "#newfile", function(e) {
		
		var model = editing.attr("model");
		
		$("#hidden").load('ajax.php?model=' + encodeURIComponent(model) + 
				'&action=newfile', function ( bool ) {
				
			$('#area_0').prepend( bool );
		});
		
		$('#pp_editor').slideUp();
		if(editing != null) 
			editing.addClass('editable').removeAttr("contenteditable");
	});
	
	$(document).on("click", "#cancel", function(e) {
		$('#pp_editor').slideUp();
		if(editing != null) {
			editing.addClass('editable').removeAttr("contenteditable");
			editing = null;
		}
	});
	
	$(document).on("click", "#inserthtml", function(e) {
		var html = prompt('Enter HTML:');
		document.execCommand('inserthtml', false, html)
	});
	
	/*function getCaretCharacterOffsetWithin(element) {
		var caretOffset = 0;
		var doc = element.ownerDocument || element.document;
		var win = doc.defaultView || doc.parentWindow;
		var sel;
		if (typeof win.getSelection != "undefined") {
			var range = win.getSelection().getRangeAt(0);
			var preCaretRange = range.cloneRange();
			preCaretRange.selectNodeContents(element);
			preCaretRange.setEnd(range.endContainer, range.endOffset);
			caretOffset = preCaretRange.toString().length;
		} else if ( (sel = doc.selection) && sel.type != "Control") {
			var textRange = sel.createRange();
			var preCaretTextRange = doc.body.createTextRange();
			preCaretTextRange.moveToElementText(element);
			preCaretTextRange.setEndPoint("EndToEnd", textRange);
			caretOffset = preCaretTextRange.text.length;
		}
		return caretOffset;
	}*/
		
	/*function showCaretPos() {
		var el = document.getElementById("d");
		console.log("Caret position: " + getCaretCharacterOffsetWithin(el));
	}*/
	

	
	is_current_state = function(button) {
		if(button.id == 'fontname') {

			var fontName = document.queryCommandValue("FontName");
			var fonts = fontName.split(",");
			$('#pp_editor #fontname option[value='+fonts[0]+']').attr("selected", true);
			
			return false;
		}
		
		if(button.id == 'fontsize') {
			
			// Font Size
			var fontSize = document.queryCommandValue("FontSize");
			$('#pp_editor #fontsize option[value='+fontSize+']').attr("selected", true);
			
			return false;
		}
		
		if(button.id == 'color_front') {
			
			var foreColor = document.queryCommandValue("ForeColor");
			$('#pp_editor #color_front').val(rgbToHex(foreColor));
			
			return false;
		}
		
		if(button.id == 'color_back') {
			
			var foreColor = document.queryCommandValue("BackColor");
			$('#pp_editor #color_back').val(rgbToHex(foreColor));
			
			return false;
		}
		
		if(button.id == 'formatblock') {			
			var block = document.queryCommandValue("formatBlock");
			
			// Font Size
			$('#pp_editor #formatblock option[value='+block+']').attr("selected", true);
			
			return false;
		}
		
		if(document.queryCommandState(button.action))
			$('#pp_editor #'+button.id).addClass("is-current-state");
		else
			$('#pp_editor #'+button.id).removeClass("is-current-state");
	}
	
	check_enabled = function() {
		
		if(editing == null) 
			return false;
		
		is_supported = function(button) { /*function(state, id)*/

			if(button.id === "edithtml") {
				return false;
			}
			
			if(!document.queryCommandEnabled(button.action) || (html && button.on_html_disabled)) {
				if(!$('#pp_editor #'+ button.id).hasClass("not-supported"))
					$('#pp_editor #'+ button.id).addClass("not-supported").attr('disabled', 'true');
			} else {
				if($('#pp_editor #'+ button.id).hasClass("not-supported"))
					$('#pp_editor #'+ button.id).removeClass("not-supported").removeAttr('disabled');
			}
			
		}
		
		for(var i = 0; i < buttons.length; i++) {
			is_supported(buttons[i]);
			
			if(!html)
				is_current_state(buttons[i]);
		}
	}
	
	var aktiv = setInterval(check_enabled, 100);
	

	
	
	/*$(document).on("click", '*[contenteditable="true"]', function(e) {
	
		
		
			
		formatBlock("bold");
		formatBlock("italic");
		formatBlock("underline");
		formatBlock("strikethrough");
		formatBlock("subscript");
		formatBlock("superscript");
		formatBlock("insertorderedlist");
		formatBlock("insertunorderedlist");
		formatBlock("insertlinebreak");
		formatBlock("blockquote");
		formatBlock("createlink");
		formatBlock("unlink");
		
		
		//showCaretPos();
		//console.log(document.queryCommandState("formatBlock", null, "p"));
		
		formatBlock("justifyleft");
		formatBlock("justifycenter");
		formatBlock("justifyright");
		formatBlock("justifyfull");
		
		$('#pp_editor #formatblock option[value=p]').attr("selected", true);
		check_enabled();
		

		//console.log(document.queryCommandState("createLink"));
			
		//console.log(document.queryCommandState("Paragraph"));
		
		//console.log("format_block" + format_block);
		
		//var fontName = document.queryCommandValue("FontName");
		//var colour = document.queryCommandValue("ForeColor");
		
		//var bold = document.queryCommandState("Bold");
		//console.log( bold: " + bold);
		//console.log('fontname: ' + fontName);
	});*/
	
	
	/*$(document).on("click", 'time[contenteditable="true"]', function(e) {
	
		$(this).attr("contenteditable", "false");
		$(this).html("");
		$(this).after('<input type="date" name="editable_date" value="2011-09-29" class="editable">');
	
		console.log('jizzz');

		//console.log(document.queryCommandState("createLink"));
			
		//console.log(document.queryCommandState("Paragraph"));
		
		//console.log("format_block" + format_block);
		
		//var fontName = document.queryCommandValue("FontName");
		//var colour = document.queryCommandValue("ForeColor");
		
		//var bold = document.queryCommandState("Bold");
		//console.log( bold: " + bold);
		//console.log('fontname: ' + fontName);
	});*/

			
	$(document).on("click", ".editable", function(e) {	
			
		if(editing != null && editing != 23) 
			editing.addClass('editable').removeAttr("contenteditable");
			
		$('#pp_editor').slideDown(function() {
		});

		$(this).removeClass('editable');
		editing = $(this);
		editing.attr("contenteditable", "true").focus();

		// Check if editing already is in history or not, we do not want any duplicate entries
		var model = $(this).attr("model");
		var id = $(this).attr("model_id");
		var key = $(this).attr("model_key");
		if(!history.find(id, model, key))
			history.push($(this));	

		console.log(history);
	});
	
	
	/*$('*[contenteditable="true"]').focusout(function(e) {
		$('#pp_editor').hide();
	});*/

	/*$('#pp_editor .button').click(function(e) {
		
		var cmd = $(this).attr('data-title');
		//var value = $(this).val() || null;
		//if (value == 'promptUser')
		//	value = prompt($(this).attr('promptText'));
			
			
		document.execCommand(cmd,false,null);


	  /*switch($(this).attr('id')) {
		case 'h1':
		case 'h2':
		case 'p':
		  document.execCommand('formatBlock', false, $(this).attr('id'));
		  break;
		default:
		  document.execCommand($(this).attr('id'), false, null);
		  break;
		}*//*
	});*/
	
	$(document).on('click', '#pp_editor button:not(.nonpermanent)', function(e) { 

		var state = $(this).attr("id");
		$('#pp_editor #'+state).toggleClass("is-current-state");
		
		/*formatBlock("justifyleft");
		formatBlock("justifycenter");
		formatBlock("justifyright");
		formatBlock("justifyfull");*/
	
	});
	
	$(document).on("change", "#fontname", function(e) {
		document.execCommand("fontname", false, $(this).val() );
	});
	
	$(document).on('change', '#fontsize', function() { 
		document.execCommand("fontsize", false, $(this).val() );
	});
	
	$(document).on('change', '#color_front', function() { 
		document.execCommand("color_front", false, $(this).val() );
	});
	
	$(document).on('change', '#color_back', function() { 
		document.execCommand("color_back", false, $(this).val() );
	});
	
	$('#formatblock').focusout(function(e) {
		document.execCommand("formatblock", false, $(this).val() );
	});
	
	$(document).on("change", '#fontSizeSelector', function(e) {
		$(this).mouseup(function(e) {
			document.execCommand("fontsize", false, $(this).val() );
			editing.focus();
		});
		
		$(document).keyup(function(e) {
			if(e.keyCode == 13)
			document.execCommand("fontsize", false, $(this).val() );
			editing.focus();
		});
	});	
	
	$(document).on("change", '#formatblock', function(e) {
		
		var execFormatBlock = function(obj){
			document.execCommand("formatblock", false, $(obj).val() );
			editing.focus();			
		}
		
		$(this).mouseup(function(e) {
			execFormatBlock(this);
		});
		
		$(document).keyup(function(e) {
			if(e.keyCode == 13)
				execFormatBlock(this);
		});
	});	
	
	
	$(document).on('click', '#pp_editor #edithtml', function(e) { 

		var button = $(this).attr("id");
		var state = $(this).hasClass('is-current-state');
		$('#pp_editor #'+button).toggleClass("is-current-state");
		html = html ? false : true;
		
		if(state) { // apply normal displaying
			var value = $(editing).text();
			$(editing).html(value).focus();
		} else { // apply html displaying
			var value = $(editing).html();
			$(editing).text(value).focus();
		}
	
	});
	
	$.ajax({
        url : "editor/views/editor.html",
        dataType: "text",
        success : function (data) {
            $("#plugable_content").append(data);
        }
    });    
	$("body").addClass("p_editor");
	// Apply padding to body
	padding = $("body").css('padding-top').split("");
	padding.pop();
	padding.pop();

	padding = parseInt(padding.join(""));
	$("body").css('padding-top', padding + 100 + 'px');
	
	$(window).on('beforeunload', function() {
		console.log(history)
		;
		if(history.getLength() > 0)
			return "You have attempted to leave this page.  If you have made any changes to the fields without clicking the Save button, your changes will be lost.  Are you sure you want to exit this page?";
	});
	editing = 23;

	// var foobar = 0;
	// $("a").on("click", function(e) {
	// 	if(!foobar) {
	// 		var answer = confirm("Follow Link?");
	// 		if(!answer) {
	// 			e.preventDefault();
	// 			foobar = 0;
	// 		}
	// 	} else {
	// 		console.log($(this));
	// 		$(this).click();
	// 		foobar = 1;
	// 	}
	// });
	
});
