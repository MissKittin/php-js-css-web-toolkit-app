<?php
	require APP_LIB.'/basic_template_config.php';
	basic_template_config($view, static::class)

	::	cache('canonical', (empty($_SERVER['HTTPS']) ? 'http' : 'https').'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'])

	::	csp('script-src', generate_csp_hash_file(__DIR__.'/main.js'))
	::	csp('script-src', generate_csp_hash_file(TK_LIB.'/getJson.js'))
	::	csp('style-src', generate_csp_hash_file(__DIR__.'/main.css'))

	::	lang('en_US')
	::	title('multipage.js test')
	::	meta_description('multipage.js library test')
	::	meta_robots('index,follow')
	::	link_canonical(basic_template_cache('canonical'))

	::	scripts(app_template::get_public_dir_url().'/assets/multipage.js')

	::	og('url', basic_template_cache('canonical'))
	::	og('type', 'website')
	::	og('site_name', 'PHP JS CSS Web Toolkit App')
; ?>