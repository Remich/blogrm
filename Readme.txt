Todo:
=====
	
	* (done) grep nach modules und removen
	* (done) Sorting stuff removen

	* (done) $_db als Referenz entlang des Controllers weiterreichen
	* (done) implement a MySql database;
	* (done) Apply trim to all ajax results!!
	* (done) Rewrite MySQL queries for SQLite
	* (done) completely remove bookmarks


	* (100) USE prepared statements everywhere!!!
	* (100) fix sqlite error in RSS-Feed
	* (50) make tables hardcoded in 
		TAGS, RELATION, ARTITCLE etc...
	* (1000) new entries of Article, Tag or whatever HAVE TO BE REMOVED from the constructor
		-> otherwise anybody visitor could just create dozens of entries, just by manipulating the url

	CURRENTLY:
		* (DONE) make articles viewable by month and year
		* (DONE) create ListOfYears
		* finish styling of blueappeal
		
	* 1 * rename Methods according to Coding Standards 
	* rename News to ListOfArticles
	* 10 * fix security hole with $_GET parameters
	* 10 * make Config.inc.php protected!!!!!!!
	* 10 * implement multiple users
	* 7 * implement child themes and child models
	* 10 * implement markdown support
	* fileupload testen / besser machen
		* FileUpload.class.php aufräumen

	* remove field a_sort from database scheme
	* salt passwords

	Panel:
	=======
	* Autoload 
		* entries in admin-panel/views/panel.php
		* switch styles in admin-panel/views/panel.css
		* cases in toggle.php
		* css- / js files in Controller.class.php (line 30ff)
	* Make Login / Logout extra Plugin

	Themes:
	=======
		* deadlanguage theme / editor? unsassen
		* image-width über theme stylen

	
	Editor:
	=======

		* (DONE) protect session froms being hijacked by other projects on the same server
		* 10 – implement styleless editable fields, otherwise user fucks up layout
			* e.g. h1 of article should not be stylable
		* 9 – improve UI & Usability of Image / File Upload and Image / File / Link inserting ( 20 Points )

		* make edithtml button disabled, when no item has focus
		* fix uhrzeit bei new entry und sqlite
		* (DONE) remove googleprettify
	
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
		* (DONE) 10 – Deleting an entry and then saving any other entry/item, causes the deleted item of the entry to be saved again, because it still is in the history array

		* keine kommentare laden, wenn man nicht den einzelnen Eintrag ansieht

		* 7 * prüfe ob ungespeicherte änderungen vorhanden sind -> methode verbessern
		* 7 add error message, when request item, article, tag, could not be found
		* 10 – when editing html and switching to other article and again switching to edit html fucks up

		* 5 fix bug, when removing style blockquote
		* 3 blockquote cite="author":
		* 2 * comment and clean up pp_editor.js
		* 1 – add Inline SVG to config of htmlpurifier
		* 2 – Can't leave prettyprinted <code>, workaround -> edit html 
		* wrap history array in an object	
		* fix NaN with RGB somewhere, see console

		
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
