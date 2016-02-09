<?php 
	header('Content-Type: text/xml; charset=utf-8');
	$str = '<?xml version="1.0"?>';
	$str .= '<rss version="2.0">';
	$str .= '<channel>';
	$str .= '<title>René Michalke</title>';
	$str .= '<link>http://www.renemichalke.de</link>';
	$str .= '<description>René Michalke Blog RSS Feed</description>';
	foreach($this->_['data']['content'] as $key => $item) {
		$str .= $item;
	}
	$str .= '</channel>';
	$str .= '</rss>';
	echo $str;
?>