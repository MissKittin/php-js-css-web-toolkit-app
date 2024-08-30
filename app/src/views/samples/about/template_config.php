<?php
	$view['csp_header']['script-src'][]=generate_csp_hash_file(__DIR__.'/markdown.js');
	$view['csp_header']['style-src'][]=generate_csp_hash_file(__DIR__.'/markdown.css');

	$view['lang']='en';
	$view['title']='About';
	$view['meta_description']='Toolkit readme';
	$view['meta_robots']='index,follow';
?>