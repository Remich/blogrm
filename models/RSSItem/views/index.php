<?php foreach($this->_['data'] as $key => $item) { ?>
<item>
<title><?php echo $item['title']; ?></title>
<link>http://www.renemichalke.de/index.php?page=post&amp;id=<?php echo $item['id']; ?></link>
<description><?php echo $item['content']; ?></description>
<category><?php echo $item['categories']; ?></category>
<pubDate><?php echo $item['a_date_rss']; ?></pubDate>
<source url="http://www.renemichalke.de/index.php">René Michalke – Blog</source>
</item>
<?php } ?>
