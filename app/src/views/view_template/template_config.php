<?php
	php_debugbar::get_template_config($view);

	//$view['_csp_header']['script-src'][]='\'sha256-something\'';

	$view['_lang']='en';
	$view['_title']='Page title';
	$view['_meta_description']='Page description';
	$view['_meta_robots']='index,follow';

	$view['_opengraph_headers'][]=['url', (empty($_SERVER['HTTPS']) ? 'http' : 'https').'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']];
	$view['_opengraph_headers'][]=['type', 'website'];
	$view['_opengraph_headers'][]=['site_name', 'My Awesome Website'];
	//$view['_opengraph_headers'][]=['image', (empty($_SERVER['HTTPS']) ? 'http' : 'https').'://'.$_SERVER['HTTP_HOST'].app_template::get_public_dir_url().'/assets/website-logo.jpg'];
	//$view['_opengraph_headers'][]=['image:type', 'image/jpeg'];
	//$view['_opengraph_headers'][]=['image:width', '400'];
	//$view['_opengraph_headers'][]=['image:height', '300'];
	//$view['_opengraph_headers'][]=['image:alt', 'Red Bone'];
	//$view['_opengraph_headers'][]=['locale:alternate', 'fr_FR'];

	//$view['_meta_name']['name']='value';
	//$view['_meta_property']['property']='value';
	//$view['_html_headers'].='<my-header tag>'; // note: .= can "PHP Notice:  Undefined variable $view['_html_headers']"
	//static::$favicon=__DIR__.'/favicon.html';

	//$view['_styles'][]=[app_template::get_public_dir_url().'/assets/style.css']; // or ['https://another.server/style.css', 'sha384-hash', 'anonymous']
	//$view['_scripts'][]=[app_template::get_public_dir_url().'/assets/script.js']; // or ['https://another.server/script.js', 'sha384-hash', 'anonymous']
	//$view['_scripts'][]=[app_template::get_public_dir_url().'/assets/module.js', null, null, 'module']; // or ['https://another.server/module.js', 'sha384-hash', 'anonymous', 'module']
	//$view['_scripts_top'][]=[app_template::get_public_dir_url().'/assets/script.js']; // or ['https://another.server/script.js', 'sha384-hash', 'anonymous']
	//$view['_scripts_top'][]=[app_template::get_public_dir_url().'/assets/script.js', null, null, null, 'defer']; // or ['https://another.server/script.js', 'sha384-hash', 'anonymous', null, 'defer']
	//$view['_scripts_top'][]=[app_template::get_public_dir_url().'/assets/module.js', null, null, 'module']; // or ['https://another.server/module.js', 'sha384-hash', 'anonymous', 'module']

	// Bootstrap integration - cdn.jsdelivr.net
		//$view['_csp_header']['style-src'][]='https://cdn.jsdelivr.net';
		//$view['_csp_header']['script-src'][]='https://cdn.jsdelivr.net';

		//static
		//::	disable_default_styles()
		//::	disable_default_scripts();

		//$view['_styles'][]=['https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css', 'sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65', 'anonymous'];
		//$view['_scripts'][]=['https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js', 'sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4', 'anonymous'];

	// Bootstrap integration - local assets
		//static
		//::	disable_default_styles()
		//::	disable_default_scripts();

		//$view['_styles'][]=[app_template::get_public_dir_url().'/assets/bootstrap.min.css', null, null];
		//$view['_scripts'][]=[app_template::get_public_dir_url().'/assets/bootstrap.bundle.min.js', null, null];

	$view['my_function']=function($text)
	{
		return '<span style="color: red;">'.$text.'</span>';
	};

	$view['my_variable']='my variables value';
?>