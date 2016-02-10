<!DOCTYPE html>
<html lang="de">
	<head>
		<meta http-equiv="Content-Type" content="text/html;" charset="UTF-8" />
		<title><?php echo $this->_['page_title']; ?></title>
		<?php echo $this->_['header']; ?> 
		<link rel="alternate" type="application/rss+xml" title="<?php echo $this->_['page_title']; ?> – RSS Feed" href="index.php?page=rss" />

		<link rel="stylesheet" href="<?php echo get_theme_folder(); ?>cabin/stylesheet.css" /> 
		<link rel="stylesheet" href="<?php echo get_theme_folder(); ?>style.css" />
	</head>
	<body id="<?php echo $this->_['page']; ?>">
		<div id="plugable_content" style="position: fixed; width: 100%; top: 0px !important; z-index: 1;"></div>
		<div id="hidden" style="visibility: hidden; height: 0px; overflow: hidden;"></div>

		<div id="wrapper">

			<header id="head">
				<a href="index.php"><h1 id="logo"><span id="r">r</span><span id="m">m</span><span id="m2">m</span></h1>
				<h2 id="rm"><span>rene</span>michalke</h2></a>

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
			</header>

			<hr class="style-two" width="600">
			<hr class="style-three" width="600">

			<?php if (@$this->_['areas'][0]) { ?>
			<div id="area_0">
				<?php foreach($this->_['areas'][0] as $item) { ?>
					<?php echo $item; ?>
				<?php } ?>
			</div>
			<div class="clear"></div>
			<hr class="style-two" width="600">
			<hr class="style-three" width="600">
			<?php } ?>

			<?php if (@$this->_['areas'][1]) { ?>
			<div id="area_2">
				<?php foreach($this->_['areas'][1] as $item) { ?>
					<?php echo $item; ?>
					<hr class="style-two" width="600">
					<hr class="style-three" width="600">
				<?php } ?>
			</div>
			<?php } ?>

			<?php if(@$this->_['areas'][2]) { ?>
			<div id="area_1">
				<?php foreach($this->_['areas'][2] as $item) { ?>
					<?php echo $item; ?>
				<?php } ?>
			</div>
			<hr class="style-two" width="600">
			<hr class="style-three" width="600">
			<?php } ?>


			<footer>
				<section id="copy"><br>COPYRIGHT © 2016 <strong>René Michalke</strong> – <a href="index.php?page=impressum">Impressum</a> | <a href="toggle.php?item=admin-panel">Admin</a>
				<br><?php echo $this->_['footer']; ?> 
				</section>
			</footer>

		</div>

		<script type="text/javascript" src="<?php echo get_theme_folder(); ?>extensions/google-code-prettify/prettify.js"></script>
		<script type="text/javascript">
			$( document ).ready( function() {
			
				prettyPrint();
				
				$('#rm').animate({
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
				});
			});
		</script>
		
	</body>
</html>