<section id="ListOfMonths">
	<header>
		<h1>Articles by Month</h1>
	</header>
	<ul>
	<?php foreach($this->_['data']['content'] as $key => $item) { ?>
		<li><a href="index.php?month=<?php echo $item['month_numeric']; ?>&year=<?php echo $item['year']; ?>"><?php echo $item['year']." – ".$item['month']; ?></a></li>
	<?php } ?>
	<ul>
</section>
