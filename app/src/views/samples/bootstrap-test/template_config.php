<?php
	$view['csp_header']['style-src'][]=generate_csp_hash_file(__DIR__.'/style.css');

	$view['lang']='en_US';
	$view['title']='Bootstrap test';
	$view['meta_description']='Bootstrap framework test';
	$view['meta_robots']='index,follow';

	static
	::	disable_default_styles()
	::	disable_default_scripts();

	if(file_exists(APP_ROOT.'/public/assets/bootstrap.min.css'))
	{
		log_infos()->info('views/bootstrap-test: using local bootstrap.min.css');
		$view['styles'][]=[app_template::get_public_dir_url().'/assets/bootstrap.min.css', null, null];
	}
	else
	{
		log_infos()->info('views/bootstrap-test: using bootstrap.min.css from cdn.jsdelivr.net');

		$view['csp_header']['style-src'][]='https://cdn.jsdelivr.net';
		$view['styles'][]=['https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css', 'sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65', 'anonymous'];
	}

	if(file_exists(APP_ROOT.'/public/assets/bootstrap.bundle.min.js'))
	{
		log_infos()->info('views/bootstrap-test: using local bootstrap.bundle.min.js');
		$view['scripts'][]=[app_template::get_public_dir_url().'/assets/bootstrap.bundle.min.js', null, null];
	}
	else
	{
		log_infos()->info('views/bootstrap-test: using bootstrap.bundle.min.js from cdn.jsdelivr.net');

		$view['csp_header']['script-src'][]='https://cdn.jsdelivr.net';
		$view['scripts'][]=['https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js', 'sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4', 'anonymous'];
	}

	$view['opengraph_headers'][]=['url', (empty($_SERVER['HTTPS']) ? 'http' : 'https').'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']];
	$view['opengraph_headers'][]=['type', 'website'];
	$view['opengraph_headers'][]=['site_name', 'PHP JS CSS Web Toolkit App'];
?>