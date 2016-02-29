# TODO #

	* (DONE) grep nach modules und removen
	* (DONE) Sorting stuff removen
		* (DONE) remove field a_sort from database scheme

	* (DONE) $_db als Referenz entlang des Controllers weiterreichen
	* (DONE) implement a MySql database;
	* (DONE) Apply trim to all ajax results!!
	* (DONE) Rewrite MySQL queries for SQLite
	* (DONE) completely remove bookmarks
	* (DONE) find why there is a tab at the beginning of each file, especially in RSS-Feed
	* (DONE) get info of rss feed from config
	* (DONE) get language for <html lang="?"> from config

	* grep nach TODOs
	* delete old jquery version 

## SECURITY ##

	* (DONE) fix sqlite error in RSS-Feed
	* (DONE) use prepared statements everywhere!!!
	* (DONE) new entries of Article, Tag or whatever HAVE TO BE REMOVED from the constructor
		-> otherwise any visitor could just create dozens of entries, just by manipulating the url
		* (DONE) FIX error, see pp_editor.js line 277
	* (DONE) Fix RSS
	* (DONE) FIX delete_item in pp_editor.js
	* (DONE) Make constructors defensive against passed parameters
		(use implicit typecasting)
		- Article
		- ListOfArticles
		- ListOfMonth
		- ListOfYears
		- RSSFeed
		- RSSItem
		- Relation
		- Tag
		- TagCloud
		- TagManager
		- Model
		- Pages
	* (DONE) Say from where the error message comes from
	* (DONE) fix / escape / make save loading of models in ControllerAjax.class.php
		<=> probably the same <=>
	* (DONE) fix security hole with $_GET parameters
		(save, delete, new_file, …)

## NEW FEATURES ##

	* (DONE) make articles viewable by month and year
	* (DONE) create ListOfYears
	* implement working comments
		* make comments editable
	* make article date editable	
	* make Article/views/single.php fresh for new data passing

	* implement real static content
	* write installation-script
		** with salted password!!!
		** make Config.inc.php protected!!!!!!!

## MODELS ##

	* make views of models look nice without any styling
		** then modify themes

## THEMES ##

	* (DONE) finish styling of blueappeal
	* (DONE) accustom blueappeal, pineapple to new content passing
		* (DONE) blueappeal
		* (DONE) pineapple
	* rework deadlanguage from scratch
	* create alternative for non renemichalke.de logos
	* image-width über theme stylen
	* blueappeal: indent has same styling as blockquote!!

## PANEL ##

	* Autoload:
		* entries in admin-panel/views/panel.php
		* switch styles in admin-panel/views/panel.css
		* cases in toggle.php
		* css- / js files in Controller.class.php (line 30ff)
	* Make Login / Logout extra Plugin

## EDITOR ##

	* (DONE) protect session froms being hijacked by other projects on the same server
	* fix is_current_state of formatblock, fontsize, fontname in firefox!!
	* (DONE) remove every occurrence of prettyprint
	* implement styleless editable fields, otherwise user fucks up layout
		* e.g. h1 of article should not be stylable
	* improve UI & Usability of Image / File Upload and Image / File / Link inserting
	* make edithtml button disabled, when no item has focus
	* fix uhrzeit bei new entry und sqlite
	* (DONE) remove googleprettify
	
## Blog ##

	* (DONE) conflict between prettify and color of font
	* (DONE) insert horizontalruler disabled to due inserthtml
	* (DONE) implement ability to create sublists – just use indent button of editor
	* (DONE) fix trimming in tags
	* (DONE) authentifictation and protected areas/ajax requests
		* (DONE) add $bouncer to protected Areas
		* (DONE) nicen the ajax login-prompt
		* (DONE) implement bruteforce protection on server-side!
	* (DONE BY ACCIDENT) 10 * implement saving of alle edited articles
		* (DONE) fix bug: new article -> edit heading -> edit message -> edit tags -> save ==> NOT SAVING THE 	FUCKING TITLE
 	* (DONE) 8 * !!Backslashes like \\\ are being deleted, when saving a prettyprint code paragraph
	* (DONE) 10 – modify config of htmlpurifier
	* (DONE) 7 – add missing html elements to prettify config
		* (DONE) Audio, Video, Canvas, IFRAME
	* (DONE) 10 – Deleting an entry and then saving any other entry/item, causes the deleted item of the entry to be saved again, because it still is in the history array
	* (DONE) prüfe ob ungespeicherte änderungen vorhanden sind -> methode verbessern
	* (DONE) add error message, when request item, article, tag, could not be found
	* (DONE) wrap history array in an object	
	* (DONE) keine kommentare laden, wenn man nicht den einzelnen Eintrag ansieht
	
	
	* when editing html and switching to other article and again switching to edit html fucks up
	* fix bug, when removing style blockquote
	* blockquote cite="author":
	* fix NaN with RGB somewhere, see console
		
## Architecture ##
					
	* (DONE) Entferne MultipleConstructors();
	* (DONE) DBSql wieder zu static DB machen -> less dependencies
	* (DONE) move switches from themes to core function
		
## TagManager ##
	
	* (DONE) Create class Tag, which behaves like class Article
	* (DONE) Rewrite CategoryManager according to class Tag
	* (DONE) Rename CategoryManager to TagManager
	* (DONE) Generalize TagManager to handle other types of modles ( currently it handles only Article-Models)

## CLEAN UP ##

	* (DONE) rename Categories to Tags (everywhere)
	* (DONE) rename News to ListOfArticles
	* (DONE) rename table article -> articles
	* comment everything
	* rename Methods according to Coding Standards 
	* check start and end of class for correct comments
	* rewrite copyrights
	* (DONE) remove unused classes from folder classes
		-> put them somewhere else

## Version 1.1 ##
	* implement multiple users
	* implement markdown support
	* improve fileupload
		* cleanup FileUpload.class.php
	* implement child themes and child models