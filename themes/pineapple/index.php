<!DOCTYPE HTML>
<html lang="de">
	<head>	
		<meta charset="utf-8" />
		<title>renemichalke.de</title>
		<?php echo $this->_['header']; ?> 
		<link rel="alternate" type="application/rss+xml" title="René Michalke – Blog – RSS Feed" href="index.php?page=rss" />
		<link rel="shortcut icon" href="<?php echo get_theme_folder(); ?>img/favicon.ico" />
		<link rel="stylesheet" href="<?php echo get_theme_folder(); ?>cabin/stylesheet.css" /> 
		<link rel="stylesheet" href="<?php echo get_theme_folder(); ?>juice/stylesheet.css" />
		<link rel="stylesheet" href="<?php echo get_theme_folder(); ?>style.css" />
		
		<link href="<?php echo get_theme_folder(); ?>extensions/google-code-prettify/desert.css" type="text/css" rel="stylesheet" />
	</head>
	<body id="<?php echo $this->_['page']; ?>">
		<div id="plugable_content" style="position: fixed; width: 100%; top: 0px !important; z-index: 1;"></div>
		<div id="hidden" style="visibility: hidden"></div>
		<div id="wr">
			<div id="wrapper">
			
				
				<header id="head">
					<hgroup>
						<a href="index.php"><h1 id="logo"><span id="r">r</span><span id="m">m</span><span id="m2">m</span></h1>
						<h2 id="rm"><span>rene</span>michalke.de</h2></a>
						<span id="icons">
				<nav>
						<ul>
							<li>
								<a href="index.php?page=blog" class="blog default">Blog</a>
							</li>
							<li>
								<a href="index.php?page=portfolio" class="portfolio">Portfolio</a>
							</li>
							<!--<li>-->
								<!--<a href="">Kontakt</a>-->
							<!--</li>-->
							<!--<li>-->
								<!--<a href="">Software</a>-->
							<!--</li>-->
							<!--<li>-->
								<!--<a href="">About</a>-->
							<!--</li>-->
						</ul>
					</nav>
							<a href="index.php?page=rss">
								<img src="<?php echo get_theme_folder(); ?>img/rss.png">
							</a>
						</span>
					</hgroup>
				</header>
				
				<!--<hr id="hr-1">-->

				<?php if (@$this->_['news']) { ?>
				<section id="content" class="blog">
					<?php echo @$this->_['news']; ?>			
				</section>	
				<?php } ?>

				<?php echo @$this->_['content']; ?>	
			</div>
			<div id="wrapper-2">
				<footer>
					<section id="sec_1">
						<?php echo $this->_['static_hi']; ?>
					</section>
					  
					<section id="sec_2">
						<?php echo $this->_['tagcloud']; ?>
					</section>
				</footer>
			</div>
			<!--<div id="wrapper">-->
				<footer>
					<div id="copy"><br>COPYRIGHT © 2014 <strong>René Michalke</strong> – <a href="index.php?page=impressum">Impressum</a>
					<br><?php echo $this->_['footer']; ?> 
					</div>
				</footer>
			</div>
		</div>
		<!--<script src="<?php echo get_theme_folder(); ?>extensions/jquery-2.0.3.min.js"></script>-->
		<script type="text/javascript" src="<?php echo get_theme_folder(); ?>extensions/google-code-prettify/prettify.js"></script>
		<script type="text/javascript">
			$( document ).ready( function() {
			
				prettyPrint();
				
				/*$('#rm').animate({
						width: 'toggle'
					}, 0);
				$('#m2').mouseover( function() { 
					$('#rm').stop(true, false).animate({
						width: 'toggle',
						opacity: 'toggle'
					});
				});
				$('#m2').mouseout( function() { 
					$('#rm').stop(true, false).animate({
						width: 'toggle',
						opacity: 'toggle'
					});
				});*/
			});
		</script>
	</body>
</html>

	
