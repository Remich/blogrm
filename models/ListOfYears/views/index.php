<section id="ListOfYears">
	<h1>Articles by Year</h1>
	<ul>
	<?php foreach($this->_['data']['content'] as $key => $item) { ?>
		<li><a href="index.php?year=<?php echo $item['year']; ?>"><?php echo $item['year']; ?></a></li>
	<?php } ?>
	<ul>
</section>
