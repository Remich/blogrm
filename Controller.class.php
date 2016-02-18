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
			$this->_areas = array();
			
			$this->_view = new View();
			
			$this->_footer .= '<script type="text/javascript" src="libs/jquery-2.0.3.min.js"></script>';
			$this->_footer .= '<script type="text/javascript" src="libs/colorbox/jquery.colorbox-min.js"></script>';
			$this->_header .= '<link href="libs/colorbox/colorbox.css" type="text/css" rel="stylesheet" />';

			if(@$_SESSION['admin-panel']) {
			
				$this->_header .= '<link href="admin-panel/views/panel.css" type="text/css" rel="stylesheet" />';
				$this->_footer .= '<script type="text/javascript" src="admin-panel/admin-panel.js"></script>';
				$this->_footer .= '<script src="libs/sha256.js" type="text/javascript"></script>';
			
			}

			if(@$_SESSION['editor']) {
				$this->_header .= '<link rel="stylesheet" type="text/css" href="editor/views/editor.css" media="all" />';
				$this->_footer .= '<script type="text/javascript" src="editor/views/pp_editor.js"></script>';
			}
						
			//TODO: Move to helpers.inc.php
			function get_theme_folder() {
				return "themes/".Config::getOption("theme")."/";
			}

			// require_once("models/NavigationFromFolder/NavigationFromFolder.class.php");
			// $nav = new NavigationFromFolder('../');
			// $this->_view->assign('navigation', $nav->display());

			$this->_request['page'] = @$this->_request['page'] ? $this->_request['page'] : "blog";
			$this->_view->assign('page', $this->_request['page']);
			
			$_SESSION['currentURL'] = Url::getCurrentUrl();


			// Navigation
			$navigation = array();
			$i=0;
			$navigation[$i]['name'] = "All";
			$navigation[$i++]['url'] = "index.php";
			$navigation[$i]['name'] = "Manifest";	
			$navigation[$i++]['url'] = "index.php?page=post&id=678";
			$navigation[$i]['name'] = "Diary";
			$navigation[$i++]['url'] = "index.php?tag_id=27";
			$navigation[$i]['name'] = "Tags";
			$navigation[$i++]['url'] = "#area_1";
			$navigation[$i]['name'] = "Archive";
			$navigation[$i++]['url'] = "index.php?page=archive";
			$navigation[$i]['name'] = "Login";
			$navigation[$i++]['url'] = "toggle.php?item=admin-panel";

			$this->_view->assign('navigation', $navigation);

			// Page Title
			$this->_view->assign('page_title', Config::getOption("page_title"));

		} // <!-- end function ’__construct()’ -->

		public function singleArticle($id) {
			require_once("models/Article/Article.class.php");
			$article = new Article(array("id"=>$id));
			$article->setTemplate("teaser");
			echo $article->display("Article");
		}
		
		/**
		* Running the actual application
		*/
		public function control() {
			
			switch(@$this->_request['page']) {

				default:
				case "default":
				case "blog":
					
					// TODO: in klasse auslagern
					// if(isset($this->_request['tag_id'])) {
					// 	require_once("models/Tag/Tag.class.php");
					// 	$tags = explode(".", $this->_request['tag_id']);
					// 	$names = array();
					// 	$i = 0;
					// 	foreach($tags as $item) {
					// 		$tag = new Tag(array('table'=>'tags', 'id'=>$item));
					// 		$names[$i]['id'] = $item;
					// 		$names[$i++]['name'] = $tag->getName();
					// 		$tag->incHit();
					// 	}
					// 	$this->_view->assign('tagnames', $names);
					// }


					// Testing
					// require_once("models/Article/Article.class.php");

					// $test_ar = array("data" => array(
					// 	"title" => "asfdsadf ",
					// 	"content" => " fobar",
					// 	"tags" => "Unc "
					// 	));
					// $test = new Article($test_ar);
					// $test = new Article();

					// Misc::dump($test);
					// die();
						
					// News
					require_once("models/News/News.class.php");
					$news = new News($this->_request);
					$this->_areas[0][] = $news->display();
				
					// Tagcloud
					require_once("models/TagCloud/TagCloud.class.php");
					$tags = new TagCloud(/*@$this->_request['tag_id']*/);
					$tags->setTableTags("tags");
					$tags->setTableRelation("rel_articles_tags");
					$tags->setFontMax(128);
					$tags->setFontMin(8);
					if(isset($this->_request['page']))
						$tags->setPage($this->_request['page']);
					$tags->generate();
					$this->_areas[1][] = $tags->display(false);

					$this->_view->setTemplate('index');
				
					break; // <!-- end case ’default’ --> 
					
				
				case 'rss':
					require_once("models/RSSFeed/RSSFeed.class.php");
					$rss = new RSSFeed();
					die($rss->display());
				break;

				case 'archive':
					// Articles by Month
					require_once("models/ListOfMonths/ListOfMonths.class.php");
					$lom = new ListOfMonths();
					$this->_areas[0][] = $lom->display();

					// Articles by Year
					require_once("models/ListOfYears/ListOfYears.class.php");
					$loy = new ListOfYears();
					$this->_areas[0][] = $loy->display();

					$this->_view->setTemplate('index');
				break;

				case 'post':

					$this->isInRequest(array('id'));

					// Article
					require_once("models/Article/Article.class.php");
					$post = new Article(array('id'=>$this->_request['id']));
					$post->setTemplate("single");
					$this->_areas[0][] = $post->display();

					// Tagcloud
					require_once("models/TagCloud/TagCloud.class.php");
					$tags = new TagCloud(/*@$this->_request['tag_id']*/);
					$tags->setTableTags("tags");
					$tags->setTableRelation("rel_articles_tags");
					$tags->setFontMax(128);
					$tags->setFontMin(8);
					if(isset($this->_request['page']))
						$tags->setPage($this->_request['page']);
					$tags->generate();
					$this->_areas[2][] = $tags->display(false);

					// Articles by Month
					require_once("models/ListOfMonths/ListOfMonths.class.php");
					$lom = new ListOfMonths();
					$this->_areas[1][] = $lom->display();

					// Articles by Year
					require_once("models/ListOfYears/ListOfYears.class.php");
					$loy = new ListOfYears();
					$this->_areas[1][] = $loy->display();

					$this->_view->setTemplate('index');

				break;

				case 'portfolio':
					$innerView = new View();
					$innerView->setTemplate("portfolio");

					$this->_areas[0][] = $innerView->loadTemplate()	;
					$this->_view->setTemplate('index');
					// $this->_view->assign('areas', $innerView->loadTemplate());


					// require_once("models/Article/Article.class.php");
					// $article = new Article(array("id"=>"1"));
					// $article->setTemplate("teaser");
					// $this->_view->assign('static_hi', $article->display("Article"));

					// $article = new Article(array("id"=>"3"));
					// $article->setTemplate("teaser");
					// $this->_view->assign('tagcloud', $article->display("Article"));

					break;

				case 'impressum':
					$innerView = new View();
					$innerView->setTemplate("impressum");
					$this->_view->assign('content', $innerView->loadTemplate());

					require_once("models/TagCloud/TagCloud.class.php");
					$tags = new TagCloud(/*@$this->_request['tag_id']*/);
					$tags->setTableTags("tags");
					$tags->setTableRelation("rel_articles_tags");
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
			$this->_view->assign('areas', $this->_areas);
			return $this->_view->loadTemplate();
		} // <!-- end function ’display()’ -->
		
	} // <!-- end class ’Controller’ -->   
?>
