$(document).ready(function() {
	
	var history = new Array();
	var editing = null;
	var html = false;
	
	var c = 0;
	var buttons = new Array();
	
	buttons[c] = new Array();
	buttons[c].id = 'newfile';
	buttons[c].action = 'inserthtml';
	buttons[c++].disabled_when_html = false;
	
	buttons[c] = new Array();
	buttons[c].id = 'save';
	buttons[c].action = 'inserthtml';
	buttons[c++].disabled_when_html = false;
	
	/*buttons[c] = new Array();
	buttons[c].id = 'deletefile';
	buttons[c].action = 'inserthtml';
	buttons[c++].disabled_when_html = false;*/
	
	buttons[c] = new Array();
	buttons[c].id = 'undo';
	buttons[c].action = 'undo';
	buttons[c++].disabled_when_html = true;
	
	buttons[c] = new Array();
	buttons[c].id = 'redo';
	buttons[c].action = 'redo';
	buttons[c++].disabled_when_html = true;
	
	buttons[c] = new Array();
	buttons[c].id = 'removeformat';
	buttons[c].action = 'removeformat';
	buttons[c++].disabled_when_html = true;
	
	buttons[c] = new Array();
	buttons[c].id = 'fontname';
	buttons[c].action = 'fontname';
	buttons[c++].disabled_when_html = true;
	
	buttons[c] = new Array();
	buttons[c].id = 'fontsize';
	buttons[c].action = 'fontsize';
	buttons[c++].disabled_when_html = true;
	
	buttons[c] = new Array();
	buttons[c].id = 'forecolor';
	buttons[c].action = 'forecolor';
	buttons[c++].disabled_when_html = true;
	
	buttons[c] = new Array();
	buttons[c].id = 'backcolor';
	buttons[c].action = 'backcolor';
	buttons[c++].disabled_when_html = true;
	
	buttons[c] = new Array();
	buttons[c].id = 'formatblock';
	buttons[c].action = 'formatblock';
	buttons[c++].disabled_when_html = true;
		
	buttons[c] = new Array();
	buttons[c].id = 'bold';
	buttons[c].action = 'bold';
	buttons[c++].disabled_when_html = true;
	
	buttons[c] = new Array();
	buttons[c].id = 'italic';
	buttons[c].action = 'italic';
	buttons[c++].disabled_when_html = true;
	
	buttons[c] = new Array();
	buttons[c].id = 'underline';
	buttons[c].action = 'underline';
	buttons[c++].disabled_when_html = true;
	
	buttons[c] = new Array();
	buttons[c].id = 'strikethrough';
	buttons[c].action = 'strikethrough';
	buttons[c++].disabled_when_html = true;
	
	buttons[c] = new Array();
	buttons[c].id = 'subscript';
	buttons[c].action = 'subscript';
	buttons[c++].disabled_when_html = true;
	
	buttons[c] = new Array();
	buttons[c].id = 'superscript';
	buttons[c].action = 'superscript';
	buttons[c++].disabled_when_html = true;
	
	buttons[c] = new Array();
	buttons[c].id = 'justifyleft';
	buttons[c].action = 'justifyleft';
	buttons[c++].disabled_when_html = true;
	
	buttons[c] = new Array();
	buttons[c].id = 'justifycenter';
	buttons[c].action = 'justifycenter';
	buttons[c++].disabled_when_html = true;
	
	buttons[c] = new Array();
	buttons[c].id = 'justifyright';
	buttons[c].action = 'justifyright';
	buttons[c++].disabled_when_html = true;
	
	buttons[c] = new Array();
	buttons[c].id = 'justifyfull';
	buttons[c].action = 'justifyfull';
	buttons[c++].disabled_when_html = true;
	
	buttons[c] = new Array();
	buttons[c].id = 'indent';
	buttons[c].action = 'indent';
	buttons[c++].disabled_when_html = true;
	
	buttons[c] = new Array();
	buttons[c].id = 'outdent';
	buttons[c].action = 'outdent';
	buttons[c++].disabled_when_html = true;
	
	buttons[c] = new Array();
	buttons[c].id = 'insertorderedlist';
	buttons[c].action = 'insertorderedlist';
	buttons[c++].disabled_when_html = true;
	
	buttons[c] = new Array();
	buttons[c].id = 'insertunorderedlist';
	buttons[c].action = 'insertunorderedlist';
	buttons[c++].disabled_when_html = true;
	
	buttons[c] = new Array();
	buttons[c].id = 'inserthorizontalruler';
	buttons[c].action = 'inserthtml';
	buttons[c++].disabled_when_html = true;
	
	buttons[c] = new Array();
	buttons[c].id = 'blockquote';
	buttons[c].action = 'formatblock';
	buttons[c++].disabled_when_html = true;
	
	buttons[c] = new Array();
	buttons[c].id = 'createlink';
	buttons[c].action = 'createlink';
	buttons[c++].disabled_when_html = true;
	
	buttons[c] = new Array();
	buttons[c].id = 'unlink';
	buttons[c].action = 'unlink';
	buttons[c++].disabled_when_html = true;
	
	buttons[c] = new Array();
	buttons[c].id = 'inserthtml';
	buttons[c].action = 'inserthtml';
	buttons[c++].disabled_when_html = true;
	
	buttons[c] = new Array();
	buttons[c].id = 'insertimage';
	buttons[c].action = 'insertimage';
	buttons[c++].disabled_when_html = true;
	
	buttons[c] = new Array();
	buttons[c].id = 'imageleft';
	buttons[c].action = 'imageleft';
	buttons[c++].disabled_when_html = true;
	
	buttons[c] = new Array();
	buttons[c].id = 'imagenone';
	buttons[c].action = 'imageleft';
	buttons[c++].disabled_when_html = true;
	
	buttons[c] = new Array();
	buttons[c].id = 'imageright';
	buttons[c].action = 'imageright';
	buttons[c++].disabled_when_html = true;
	
	buttons[c] = new Array();
	buttons[c].id = 'edithtml';
	buttons[c].action = 'inserthtml';
	buttons[c++].disabled_when_html = false;
	

	
	//console.log(buttons);
	
	
	//library editor
	function componentToHex(c) { var hex = c.toString(16);
		return hex.length == 1 ? "0" + hex : hex;
	}

	function rgbToHex(str) {
		str = str.replace("rgb(", "").replace(")", "").replace(/ /g, "");
		str = str.split(",");
		return "#" + componentToHex(parseInt(str[0])) + componentToHex(parseInt(str[1])) + componentToHex(parseInt(str[2]));
	}


	var remove_prettyprint = function(item) {
		$(document).find('pre').each( function() {
			$(this).removeClass('prettyprint prettyprinted linenums');
		});
		prettyPrint();
		$(document).find('pre').each( function() {
			$(this).addClass('prettyprint linenums');
		});
	};
	var save_items = function() {
		
		data = [];
		while(history.length !== 0) {

			item = history.pop();
			remove_prettyprint(item);
			var model = item.attr("model");
			var id = item.attr("model_id");
			var key = item.attr("model_key");
			var value = item.html();

			data.push({ "model" : model, "id" : id, "key" : key, "value" : value }) ;

		}

		$("#hidden").load('ajax.php?&action=save', 
				{ data : data }, function ( bool ) {
			
			if (bool !== "#t") {
				alert('Fehler beim Speichern');
				console.log(bool);
			}
			// prettyPrint();
		});
		
	}
	
	var delete_item = function(to_delete) {
		item = to_delete.pop();
		var model = item.attr("model");
		var id = item.attr("model_id");

		if( confirm("Really Delete Object?") ) {
			$("#hidden").load('ajax.php?model=' + encodeURIComponent(model) + 
					'&action=delete&id=' + encodeURIComponent(id), function ( bool ) {
				
					if (bool === "true") {
						$('#a'+id).slideUp("slow", function() {
							this.remove();
						});
					}
				
				if(to_delete.length > 0)
					delete_item(to_delete);
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
				
			$('#content').prepend( bool );
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
		
		if(button.id == 'forecolor') {
			
			var foreColor = document.queryCommandValue("ForeColor");
			$('#pp_editor #forecolor').val(rgbToHex(foreColor));
			
			return false;
		}
		
		if(button.id == 'backcolor') {
			
			var foreColor = document.queryCommandValue("BackColor");
			$('#pp_editor #backcolor').val(rgbToHex(foreColor));
			
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
			
			if(!document.queryCommandEnabled(button.action) || (html && button.disabled_when_html)) {
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
		
		var found = false;
		for(var a = 0; a < history.length; a++) {
			var h_model = history[a].attr("model");
			var h_id = history[a].attr("model_id");
			var h_key = history[a].attr("model_key");
			
			if(h_model == model 
				&& h_id == id
				&& h_key == key) found = true;
		}
		if(!found)
			history.push($(this));	
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
	
	$(document).on('change', '#forecolor', function() { 
		document.execCommand("forecolor", false, $(this).val() );
	});
	
	$(document).on('change', '#backcolor', function() { 
		document.execCommand("backcolor", false, $(this).val() );
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
			if($(obj).val() == 'pre') {
				var listId = window.getSelection().focusNode.parentNode;
				$(listId).addClass("prettyprint linenums");
				var text = $(listId).html();
				$(listId).html(text.trim());

				// prettyPrint();
			}
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
		console.log(history);
		if(history.length > 0)
			return "You have attempted to leave this page.  If you have made any changes to the fields without clicking the Save button, your changes will be lost.  Are you sure you want to exit this page?";
	});
	editing = 23;
	
});
