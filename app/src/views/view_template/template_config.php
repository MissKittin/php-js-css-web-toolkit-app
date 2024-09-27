<?php
	//$view['csp_header']['script-src'][]='\'sha256-something\'';

	$view['lang']='en';
	$view['title']='Page title';
	$view['meta_description']='Page description';
	$view['meta_robots']='index,follow';

	$view['opengraph_headers'][]=['url', (empty($_SERVER['HTTPS']) ? 'http' : 'https').'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']];
	$view['opengraph_headers'][]=['type', 'website'];
	$view['opengraph_headers'][]=['site_name', 'My Awesome Website'];
	//$view['opengraph_headers'][]=['image', (empty($_SERVER['HTTPS']) ? 'http' : 'https').'://'.$_SERVER[HTTP_HOST].'/assets/website-logo.jpg'];
	//$view['opengraph_headers'][]=['image:type', 'image/jpeg'];
	//$view['opengraph_headers'][]=['image:width', '400'];
	//$view['opengraph_headers'][]=['image:height', '300'];
	//$view['opengraph_headers'][]=['image:alt', 'Red Bone'];
	//$view['opengraph_headers'][]=['locale:alternate', 'fr_FR'];

	//$view['meta_name']['name']='value';
	//$view['meta_property']['property']='value';
	//$view['html_headers'].='<my-header tag>'; // note: .= can "PHP Notice:  Undefined variable $view['html_headers']"
	//static::$favicon=__DIR__.'/favicon.html';

	//$view['styles'][]=['/assets/style.css', null, null]; // or ['https://another.server/style.css', 'sha384-hash', 'anonymous']
	//$view['scripts'][]=['/assets/script.js', null, null]; // or ['https://another.server/script.js', 'sha384-hash', 'anonymous']

	// Bootstrap integration - cdn.jsdelivr.net
		//$view['csp_header']['style-src'][]='https://cdn.jsdelivr.net';
		//$view['csp_header']['script-src'][]='https://cdn.jsdelivr.net';

		//static
		//::	disable_default_styles()
		//::	disable_default_scripts();

		//$view['styles'][]=['https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css', 'sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65', 'anonymous'];
		//$view['scripts'][]=['https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js', 'sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4', 'anonymous'];

	// Bootstrap integration - local assets
		//static
		//::	disable_default_styles()
		//::	disable_default_scripts();

		//$view['styles'][]=[app_template::get_public_dir_url().'/assets/bootstrap.min.css', null, null];
		//$view['scripts'][]=[app_template::get_public_dir_url().'/assets/bootstrap.bundle.min.js', null, null];

	$view['my_function']=function($text)
	{
		return '<span style="color: red;">'.$text.'</span>';
	};

	$view['my_variable']='my variables value';
?>