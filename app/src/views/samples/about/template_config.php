<?php
	php_debugbar::get_template_config($view);

	require APP_LIB.'/basic_template_config.php';
	basic_template_config($view, static::class)

	::	cache('canonical', (empty($_SERVER['HTTPS']) ? 'http' : 'https').'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'])

	::	csp('script-src', generate_csp_hash_file(__DIR__.'/markdown.js'))
	::	csp('style-src', generate_csp_hash_file(__DIR__.'/markdown.css'))

	::	lang('en_US')
	::	title('About')
	::	meta_description('Toolkit readme')
	::	meta_robots('index,follow')
	::	link_canonical(basic_template_cache('canonical'))

	::	og('url', basic_template_cache('canonical'))
	::	og('type', 'website')
	::	og('site_name', 'PHP JS CSS Web Toolkit App')
; ?>