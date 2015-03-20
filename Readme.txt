Todo:
=====

	* (done) $_db als Referenz entlang des Controllers weiterreichen
	* (done) implement a MySql database;
		
	* 1 * rename Methods according to Coding Standards 
	
	* 10 * fix security hole with $_GET parameters
	* 1 * move go and add bookmark to ajax controller, otherwise issues with sorting and going out
	* 10 * implement multiple users
	* 7 * implement child themes and child models
	
	Editor:
	=======

		* (DONE) protect session froms being hijacked by other projects on the same server
		* implement styleless editable fields, otherwise user fucks up layout
			* e.g. h1 of article should not be stylable
!
		Blog:
		=====

		* (DONE) conflict between prettify and color of font
		* (DONE) insert horizontalruler disabled to due inserthtml
		* (DONE) implement ability to create sublists – just use indent button of editor
		* (DONE) fix trimming in tags
		* (DONE) 10 * authentifictation and protected areas/ajax requests
			* (DONE) 3 – TODO: add $bouncer to protected Areas
			* (DONE) 3 – TODO: nicen the ajax login-prompt
			* (DONE) 4 – TODO: implement bruteforce protection on server-side!
			* (STATS) 18 files changed, 354 insertions(+), 64 deletions(-)
		* (DONE BY ACCIDENT) 10 * implement saving of alle edited articles
			* (DONE) fix bug: new article -> edit heading -> edit message -> edit tags -> save ==> NOT SAVING THE 	FUCKING TITLE
	 	* (DONE) 8 * !!Backslashes like \\\ are being deleted, when saving a prettyprint code paragraph
		* (DONE) 10 – modify config of htmlpurifier
		* (DONE) 7 – add missing html elements to prettify config
			* (DONE) Audio, Video, Canvas, IFRAME
	 

		* 7 * prüfe ob ungespeicherte änderungen vorhanden sind -> methode verbessern
		* 7 add error message, when request item, article, tag, could not be found
		* 10 – when editing html and switching to other article and again switching to edit html fucks up

		* 5 fix bug, when removing style blockquote
		* 3 blockquote cite="author":
		* 2 * comment and clean up pp_editor.js
		* 1 – add Inline SVG to config of htmlpurifier
		* 2 – Can't leave prettyprinted <code>, workaround -> edit html 
			

		Bookmarks:
		==========
		* // wenn man ohne änderung ein bookmark mehrmals speichert, so stacken sich die amps (e.g. language agnostic - What does it mean to &amp;amp;amp;amp;amp;quot;program to an interface&amp;amp;amp;amp;amp;quot;? - Stack Overflow)	

		
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
