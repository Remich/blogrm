Todo:
=====

	* (done) $_db als Referenz entlang des Controllers weiterreichen
	* (done) implement a MySql database;
		
	* 1 * rename Methods according to Coding Standards 
	
	* 10 * fix security hole with $_GET parameters
	* 1 * move go and add bookmark to ajax controller, otherwise issues with sorting and going out
	* 10 * implement multiple users
	
	Editor:
	=======
		* (fixed) conflict between prettify and color of font
		* (fixed) insert horizontalruler disabled to due inserthtml
		* (already fixed) implement ability to create sublists – just use indent button of editor
		* (DONE) fix trimming in tags

		* // wenn man ohne änderung ein bookmark mehrmals speichert, so stacken sich die amps (e.g. language agnostic - What does it mean to &amp;amp;amp;amp;amp;quot;program to an interface&amp;amp;amp;amp;amp;quot;? - Stack Overflow)	



		
		* 7 * prüfe ob ungespeicherte änderungen vorhanden sind -> methode verbessern
		* 2 * comment and clean up pp_editor.js
	 	* 8 * !!Backslashes like \\\ are being deleted, when saving a prettyprint code paragraph
		* 10 * implement saving of alle edited articles
			* fix bug: new article -> edit heading -> edit message -> edit tags -> save ==> NOT SAVING THE FUCKING TITLE
		* 7 add error message, when request item, article, tag, could not be found
		* 5 fix bug, when removing style blockquote
		* 3 blockquote cite="author":
		* 10 * authentifictation and protected areas/ajax requests
		* 10 (done) – modify config of htmlpurifier
		* 7 (done) – add missing html elements to prettify config
			* (done) Audio, Video, Canvas, IFRAMES
		* 1 – add Inline SVG to config of htmlpurifier

		
	Architecture:
	=============
					
		* (fixed) Entferne MultipleConstructors();
		* (fixed) DBSql wieder zu static DB machen -> less dependencies
		* Create class ArticleManager instead of News
			* Generalize ArticleManger to handle other types of content ( currently it handles only Articles )
		* (fixed) move switches from themes to core function
		
	TagManager (old: CategoryManager):
	==================================
	
		* TODO: Category Darstellung überarbeiten und linkbar machen (tags ordentlich trimmen und darstellen ohne links im post)
		* (done) Create class Tag, which behaves like class Article
		* (done) Rewrite CategoryManager according to class Tag
		* (done) Rename CategoryManager to TagManager
		* (done) Generalize TagManager to handle other types of modles ( currently it handles only Article-Models)
