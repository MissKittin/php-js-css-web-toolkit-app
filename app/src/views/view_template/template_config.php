<?php
	//$view['csp_header']['script-src'][]='\'sha256-something\'';

	$view['lang']='en';
	$view['title']='Page title';
	$view['meta_description']='Page description';
	$view['meta_robots']='index,follow';

	//$view['meta_name']['name']='value';
	//$view['meta_property']['property']='value';
	//$view['html_headers'].='<my-header tag>'; // note: .= can "PHP Notice:  Undefined variable $view['html_headers']"
	//static::$favicon=__DIR__.'/favicon.html';

	//$view['styles'][]=['/assets/style.css', null, null]; // or ['https://another.server/style.css', 'sha384-hash', 'anonymous']
	//$view['scripts'][]=['/assets/script.js', null, null]; // or ['https://another.server/script.js', 'sha384-hash', 'anonymous']

	$view['my_function']=function($text)
	{
		return '<span style="color: red;">'.$text.'</span>';
	};

	$view['my_variable']='my variables value';
?>