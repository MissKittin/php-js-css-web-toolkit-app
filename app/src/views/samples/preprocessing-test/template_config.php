<?php
	$view['lang']='en_US';
	$view['title']='Preprocessing test';
	$view['meta_description']='Nothing special';
	$view['meta_robots']='index,follow';

	$view['opengraph_headers'][]=['url', (empty($_SERVER['HTTPS']) ? 'http' : 'https').'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']];
	$view['opengraph_headers'][]=['type', 'website'];
	$view['opengraph_headers'][]=['site_name', 'PHP JS CSS Web Toolkit App'];
?>