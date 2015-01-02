Todo:
=====

	* (done) $_db als Referenz entlang des Controllers weiterreichen
	* (done) implement a MySql database;
		
	* rename Methods according to Coding Standards 
	
	* fix security hole with $_GET parameters
	* move go and add bookmark to ajax controller, otherwise issues with sorting and going out
	* implement multiple users
	
	Editor:
	=======
		* (fix) conflict between prettify and color of font
		* comment and clean up pp_editor.js
		* (fix) insert horizontalruler disabled to due inserthtml
		* (already fixed) implement ability to create sublists – just use indent button of editor
	 	* !!Backslashes like \\\ are being deleted, when saving a prettyprint code paragraph
		* wenn man ohne änderung ein bookmark mehrmals speichert, so stacken sich die amps (e.g. language agnostic - What does it mean to &amp;amp;amp;amp;amp;quot;program to an interface&amp;amp;amp;amp;amp;quot;? - Stack Overflow)	
		* prüfe ob ungespeicherte änderungen vorhanden sind -> methode verbessern
		* implement saving of alle edited articles
			* fix bug: new article -> edit heading -> edit message -> edit tags -> save ==> NOT SAVING THE FUCKING TITLE
		* (DONE) fix trimming in tags
		* add error message, when request item, article, tag, could not be found
		* fix bug, when removing style blockquote
	 
	Architecture:
	=============
					
		* (fix) Entferne MultipleConstructors();
		* (fix) DBSql wieder zu static DB machen -> less dependencies
		* Create class ArticleManager instead of News
			* Generalize ArticleManger to handle other types of content ( currently it handles only Articles )
		* (fix) move switches from themes to core function
		
	TagManager (old: CategoryManager):
	==================================
	
		* TODO: Category Darstellung überarbeiten und linkbar machen (tags ordentlich trimmen und darstellen ohne links im post)
		* (done) Create class Tag, which behaves like class Article
		* (done) Rewrite CategoryManager according to class Tag
		* (done) Rename CategoryManager to TagManager
		* (done) Generalize TagManager to handle other types of modles ( currently it handles only Article-Models)
