<?php header('Content-Type: application/xml; charset=utf-8'); ?>
<?php echo '<?xml version="1.0"?>'; ?>
<rss version="2.0">
<channel>
<title>René Michalke</title>
<link>http://www.renemichalke.de</link>
<description>René Michalke Blog RSS Feed</description>
<?php foreach($this->_['data']['content'] as $key => $item) { ?>
<?php echo $item; ?>
<?php } ?>
</channel>
</rss>
