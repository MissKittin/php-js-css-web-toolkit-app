<?php
	$view['csp_header']['script-src'][]='\'sha256-'
	.	base64_encode(hash_file('sha256', __DIR__.'/markdown.js', true))
	.	'\'';
	$view['csp_header']['style-src'][]='\'sha256-'
	.	base64_encode(hash_file('sha256', __DIR__.'/markdown.css', true))
	.	'\'';

	$view['lang']='en';
	$view['title']='About';
	$view['meta_description']='Toolkit readme';
	$view['meta_robots']='index,follow';
?>