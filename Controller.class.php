<?php

	/**
	*	Copyright 2010-2014 René Michalke.
	*/
	class Controller extends ControllerBase {

		private $_view = null; // our view object
		private $_header = null;
		private $_footer = null;
		private $_plugins = null;
		
		/**
		* Constructor
		*
		* @param Array $request, merged array of $_GET & $_POST & $_FILES
		*/
		public function __construct($request) {
			
			parent::__construct($request);

			
			
			$this->_view = new View();
			
			$this->_footer .= '<script type="text/javascript" src="libs/jquery-2.0.3.min.js"></script>';
			$this->_footer .= '<script type="text/javascript" src="libs/colorbox/jquery.colorbox-min.js"></script>';
			$this->_header .= '<link href="libs/colorbox/colorbox.css" type="text/css" rel="stylesheet" />';

			if(@$_SESSION['admin-panel']) {
			
			$this->_header .= '<link href="admin-panel/views/panel.css" type="text/css" rel="stylesheet" />';
			$this->_footer .= '<script type="text/javascript" src="admin-panel/admin-panel.js"></script>';
			
			}

			if(@$_SESSION['editor']) {
				$this->_header .= '<link href="libs/google-code-prettify/desert.css" type="text/css" rel="stylesheet" />';
				$this->_header .= '<link rel="stylesheet" type="text/css" href="editor/views/editor.css" media="all" />';
				$this->_footer .= '<script type="text/javascript" src="editor/views/pp_editor.js"></script>';
				$this->_footer .= '<script type="text/javascript" src="libs/google-code-prettify/prettify.js"></script>';
				$this->_footer .= '<script type="text/javascript">
					$( document ).ready( function() {prettyPrint(); });
				</script>';
			}
			if(@$_SESSION['sortable']) {
				$this->_header .= '<link href="sortable/sortable.css" type="text/css" rel="stylesheet" />';
				$this->_footer .= '<script type="text/javascript" src="libs/jquery-ui-1.11.0/jquery-ui.js"></script>';
				$this->_footer .= '<script type="text/javascript" src="sortable/sortable.js"></script>';
			}
						
			//TODO: Move to helpers.inc.php
			function get_theme_folder() {
				return "themes/".Config::getOption("theme")."/";
			}

			require_once("models/NavigationFromFolder/NavigationFromFolder.class.php");
			$nav = new NavigationFromFolder('../');
			$this->_view->assign('navigation', $nav->display());

			$this->_request['page'] = @$this->_request['page'] ? $this->_request['page'] : "default";
			$this->_view->assign('page', $this->_request['page']);
			
			$_SESSION['url_bookmarks'] = Url::getCurrentUrl();

		} // <!-- end function ’__construct()’ -->
		
		/**
		* Running the actual application
		*/
		public function control() {
			
			switch(@$this->_request['page']) {

				default:
				case "default":
				case "blog":
					


					/*$query = "SELECT * FROM article2";
					 $data = DB::get($query);
					
					
					//Misc::pre($data);
					
					require_once("models/Article/Article.class.php");
					require_once("models/Tag/Tag.class.php");
					foreach($data as $item) {
					$query = "SELECT * FROM rel_articles_categories2 WHERE a_id = :a_id";
					$tags = DB::get($query, array(':a_id'=>$item['id']));
					
					$str = "";
					foreach($tags as $item2) {
					$query = "SELECT * FROM categories2 WHERE id = :c_id";
					$name = DB::getOne($query, array(':c_id'=>$item2['c_id']));
					$str .= "#".$name['c_name'];
						
					}
					$str .= "#Programmieren 2";
					
					$daten = array('title' => $item['title'], 'content' => $item['content'], 'tags' => $str);
					$article = new Article(array('data'=>$daten));
					
					}
					die("done");*/
					
					// TODO: in klasse auslagern
					if(isset($this->_request['tag_id'])) {
						require_once("models/Tag/Tag.class.php");
						$tags = explode(".", $this->_request['tag_id']);
						$names = array();
						$i = 0;
						foreach($tags as $item) {
							$tag = new Tag(array('table'=>'categories', 'id'=>$item));
							$names[$i]['id'] = $item;
							$names[$i++]['name'] = $tag->getName();
							$tag->incHit();
						}
						$this->_view->assign('tagnames', $names);
					}
						
					require_once("models/News/News.class.php");
					$news = new News(@$this->_request['tag_id'], $this->_request);
					$this->_view->assign('news', $news->display() );
				
					require_once("models/TagCloud/TagCloud.class.php");
					$tags = new TagCloud(/*@$this->_request['tag_id']*/);
					$tags->setTableTags("categories");
					$tags->setTableRelation("rel_articles_categories");
					$tags->setFontMax(35);
					$tags->setFontMin(13);
					if(isset($this->_request['page']))
						$tags->setPage($this->_request['page']);
					$tags->generate();
					$this->_view->assign('tagcloud', $tags->display());
				
					require_once("models/ListOfContents/ListOfContents.class.php");
					$lof = new ListOfContents("article", @$this->_request['tag_id']);
					$this->_view->assign('list_of_contents', $lof->display("ListOfContents"));					
					
					require_once("models/Article/Article.class.php");
					$article = new Article(array("id"=>"1"));
					$article->setTemplate("teaser");
					$this->_view->assign('static_hi', $article->display("Article"));
									
					$this->_view->setTemplate('index');
				
					break; // <!-- end case ’default’ --> 
					
				case "bookmarks":
					$this->_header .= '<link href="models/ListOfBookmarks/views/listofbookmarks.css" type="text/css" rel="stylesheet" />';
					$this->_footer .= '<script type="text/javascript" src="models/ListOfBookmarks/views/listofbookmarks.js"></script>';

					
					require_once("models/TagCloud/TagCloud.class.php");		
					$tags = new TagCloud(@$this->_request['tag_id']);
					$tags->setTableTags("bookmark_tags");
					$tags->setTableRelation("rel_bitems_btags");
					#$tags->setFontMax(35);
					$tags->setFontMin(11);
					if(isset($this->_request['page']))
						$tags->setPage($this->_request['page']);
					$tags->generate();
					$this->_view->assign('tagcloud', $tags->display());
					
					
					if(isset($this->_request['tag_id'])) {
						require_once("models/Tag/Tag.class.php");
						$tag = new Tag(array('table'=>"bookmark_tags", "id"=>$this->_request['tag_id']));
						$this->_view->assign('tagname', $tag->getName());
						$tag->incHit();
					}
					
					require_once("models/ListOfBookmarks/ListOfBookmarks.class.php");
					$bookmarks = new ListOfBookmarks(@$this->_request['tag_id'], $this->_request);
					$this->_view->assign('bookmarks', $bookmarks->display() );
					
					$this->_view->setTemplate('bookmarks');
					
					break;
					
				case "go":
					$this->isInRequest( array( 'id') );
					require_once("models/Bookmark/Bookmark.class.php");
					$bookmark = new Bookmark(array('id'=>$this->_request['id']));
					$bookmark->go();
												
					break;
					
				case 'add_bookmark':
					
					$this->isInRequest(array('title', 'url'));
					require_once("models/Bookmark/Bookmark.class.php");
						
					$data = array(
							'uid' => 0,
							'title' => $this->_request['title'],
							'url' => $this->_request['url']
					);
					$bookmark = new Bookmark(array('data'=>$data));
				
					$this->_view->setTemplate('bookmarks-add');
					die($this->_view->loadTemplate());
				
				break; // <!-- end case ’add_bookmark’ -->
				
				case 'rss':
					require_once("models/RSSFeed/RSSFeed.class.php");
					$rss = new RSSFeed();
					die($rss->display());
				break;

				case 'post':

					$this->isInRequest(array('id'));
					

					require_once("models/Article/Article.class.php");
					$post = new Article(array('id'=>$this->_request['id']));
					$post->setTemplate("single");
					$this->_view->assign('news', $post->display() );

					require_once("models/TagCloud/TagCloud.class.php");
					$tags = new TagCloud(/*@$this->_request['tag_id']*/);
					$tags->setTableTags("categories");
					$tags->setTableRelation("rel_articles_categories");
					$tags->setFontMax(35);
					$tags->setFontMin(13);
					if(isset($this->_request['page']))
						$tags->setPage($this->_request['page']);
					$tags->generate();
					$this->_view->assign('tagcloud', $tags->display());

					require_once("models/Article/Article.class.php");
					$article = new Article(array("id"=>"1"));
					$article->setTemplate("teaser");
					$this->_view->assign('static_hi', $article->display("Article"));

					break;

				case 'portfolio':
					$innerView = new View();
					$innerView->setTemplate("portfolio");
					$this->_view->assign('content', $innerView->loadTemplate());


					require_once("models/Article/Article.class.php");
					$article = new Article(array("id"=>"1"));
					$article->setTemplate("teaser");
					$this->_view->assign('static_hi', $article->display("Article"));

					$article = new Article(array("id"=>"3"));
					$article->setTemplate("teaser");
					$this->_view->assign('tagcloud', $article->display("Article"));

					break;

				case 'impressum':
					$innerView = new View();
					$innerView->setTemplate("impressum");
					$this->_view->assign('content', $innerView->loadTemplate());

					require_once("models/TagCloud/TagCloud.class.php");
					$tags = new TagCloud(/*@$this->_request['tag_id']*/);
					$tags->setTableTags("categories");
					$tags->setTableRelation("rel_articles_categories");
					$tags->setFontMax(35);
					$tags->setFontMin(15);
					if(isset($this->_request['page']))
						$tags->setPage($this->_request['page']);
					$tags->generate();
					$this->_view->assign('tagcloud', $tags->display());

					require_once("models/Article/Article.class.php");
					$article = new Article(array("id"=>"1"));
					$article->setTemplate("teaser");
					$this->_view->assign('static_hi', $article->display("Article"));

					break;

				case 'comment':
					$this->isInRequest(array('id', 'author', 'comment'));
					require_once("models/Comment/Comment.class.php");
					$comment = new Comment(
						array("data" => array("a_id" => $this->_request['id'],
							"author" => $this->_request['author'],
							"comment" => $this->_request['comment'])));
					header('Location: index.php?page=post&id=' . $this->_request['id']);
					break;

				case 'login':
									
					$this->isInRequest( array('username', 'password') );
					require_once("classes/Auth.class.php");
					die( Auth::login($this->request['username'], $this->request['password']) );		
				
					break; // <!-- end case ’login’ -->
				
				case 'logout':
				
					require_once("classes/Auth.class.php");
					Auth::logout();
					header('Location: index.php');
							
					break; // <!-- end case ’logout’ -->
			} // <!-- end ’switch(@$this->request['page'])’ -->
			
			
		
		
		} // <!-- end function ’control()’ -->
		
		/**
		* Displaying the content
		*
		* @return String, the generated html code
		*/
		public function display() {
			$this->_footer .= DB::getNumberOfQueries()." queries executed, ";
			$this->_footer .= DB::getNumberOfConnections()." connection(s) used";
			
			$this->_view->assign('header', $this->_header);
			$this->_view->assign('footer', $this->_footer);
			return $this->_view->loadTemplate();
		} // <!-- end function ’display()’ -->
		
	} // <!-- end class ’Controller’ -->   
?>
