<section id="ListOfYears">
	<header>
		<h1>Articles by Year</h1>
	</header>
	<ul>
	<?php foreach($this->_['data']['content'] as $key => $item) { ?>
		<li><a href="index.php?year=<?php echo $item['year']; ?>"><?php echo $item['year']; ?></a></li>
	<?php } ?>
	<ul>
</section>
