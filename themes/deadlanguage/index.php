<!DOCTYPE html>
<html lang="en-US">  
	<head>  
		<meta http-equiv="Content-Type" content="text/html;" charset="UTF-8" />
		<title>René Michalke – Portfolio – WYSIWYG-CMS</title> 
		<?php echo $this->_['header']; ?> 
		<link rel="stylesheet" type="text/css" href="<?php echo get_theme_folder(); ?>styles/style.css" media="all" />
	</head>
	<body>
		<div id="plugable_content" style="position: fixed; width: 100%; top: 0px !important;"></div>
		<div id="hidden" style="visibility: hidden"></div>

		<header id="logo">  
			<div class="wrapper">
				<hgroup>
					<?php if(isset($this->_ ['tagnames'])) {?>
					
					<div id="tag_headings">
					<?php foreach($this->_['tagnames'] as $item) { ?>
					<h1><a href="ajax.php?action=load&id=removetag&tag_id=<?php echo $item['id']; ?>"><?php echo $item['name']; ?></a></h1>
					<?php } ?>
					</div>
					<a href="index.php?page=<?php echo $this->_['page']; ?>"><h2>&laquo; All Tags</h2></a>
					<hr noshade size="1">
					<?php } ?>
					<div id="tagcloud">
					<?php echo $this->_['tagcloud']; ?>
					</div>
				</hgroup>
	
				<navigation> 
					<?php echo $this->_['navigation']; ?>
				</navigation>  
			
			</div>
		</header>  


		<div class="wrapper"> 

			<div id="content">
			
		  		<div class="content-left">
		  			
		  			<?php echo $this->_['list_of_contents']; ?>
		  			<br>
		  			<div id="articles">
		  				<?php echo $this->_['news']; ?>
					</div>
		 		</div>
		  
		  	 	<!-- <div class="content-right">
				
					<aside>
						<h3>Archives</h3>
						<ul>
							<li><a href='http://localhost/wordpress/?m=201311'>November 2013</a></li>
						</ul>
					</aside>

					<aside>
						<h3>Meta</h3>
						<ul>
							<li><a href="http://localhost/wordpress/wp-login.php">Log in</a></li>
						</ul>
					</aside>
					
					
		  			<?php #echo $this->_['article']; ?>
		  
		  		</div>-->	  
		  		
			</div>

		</div>
	
	

		﻿<div id="rip">
			<div id="rip_1"> 
				<div id="rip_1_1">
				</div>
			</div>
		</div>
	
		<footer>
			<div class="wrapper">
				<p> 
					2013 Dead Language Theme – <a href="../../impressum.html">Contact & Legal Notice</a> – <a href="../../rss.rss">RSS</a>
				</p>
				<p>
					<?php echo $this->_['footer']; ?> 
				</p>
			</div>
		</footer>
		<div id="rip-bottom">
		</div>
	</body>  
</html>
