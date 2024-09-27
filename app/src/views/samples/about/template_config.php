<?php
	$view['csp_header']['script-src'][]=generate_csp_hash_file(__DIR__.'/markdown.js');
	$view['csp_header']['style-src'][]=generate_csp_hash_file(__DIR__.'/markdown.css');

	$view['lang']='en_US';
	$view['title']='About';
	$view['meta_description']='Toolkit readme';
	$view['meta_robots']='index,follow';

	$view['opengraph_headers'][]=['url', (empty($_SERVER['HTTPS']) ? 'http' : 'https').'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']];
	$view['opengraph_headers'][]=['type', 'website'];
	$view['opengraph_headers'][]=['site_name', 'PHP JS CSS Web Toolkit App'];
?>