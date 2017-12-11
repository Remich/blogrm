<!DOCTYPE html>
<html lang="<?php echo Config::getOption("page_language"); ?>">
	<head>  
		<meta http-equiv="Content-Type" content="text/html;" charset="UTF-8" />
		<title><?php echo $this->_['page_title']; ?></title>
		<?php echo $this->_['header']; ?> 
		<link rel="alternate" type="application/rss+xml" title="<?php echo $this->_['page_title']; ?> – RSS Feed" href="index.php?page=rss" />
		<link rel="stylesheet" type="text/css" href="<?php echo get_theme_folder(); ?>styles/style.css" media="all" />
	</head>
	<body id="<?php echo $this->_['page']; ?>">
		<div id="pluggable_content" style="position: fixed; width: 100%; top: 0px !important;"></div>
		<div id="hidden" style="visibility: hidden"></div>

		<header id="logo">  
			<div class="wrapper">
				<header>
					<h1>
						renemichalke.de
					</h1>
				</header>
		
				<?php if(@$this->_['navigation']) { ?>
				<nav>
					<ul>
					<?php foreach($this->_['navigation'] as $item) { ?>
						<li>
							<a href="<?php echo $item['url']; ?>"><?php echo $item['name']; ?></a>
						</li>
					<?php } ?>
					</ul>
				</nav>
				<?php } ?>
			
			</div>
		</header>  


		<div class="wrapper"> 

			<div id="content">

				<?php if (@$this->_['areas'][0]) { ?>
				<div id="area_0" class="blog content-left">
					<?php foreach($this->_['areas'][0] as $item) { ?>
						<?php echo $item; ?>
					<?php } ?>
				</div>
				<?php } ?>
		  
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
