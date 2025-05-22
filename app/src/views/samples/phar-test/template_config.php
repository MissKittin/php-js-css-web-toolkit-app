<?php
	php_debugbar::get_template_config($view);

	require APP_LIB.'/basic_template_config.php';
	basic_template_config($view, static::class)

	::	cache('canonical', (empty($_SERVER['HTTPS']) ? 'http' : 'https').'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'])

	::	csp('style-src', '\'sha256-Cccgmc9inkViNhOVWZetVV760aISEwpy7qiKnRigEps=\'')

	::	lang('en_US')
	::	title('Phar test')
	::	meta_description('Use toolkit in Phar')
	::	meta_robots('index,follow')
	::	link_canonical(basic_template_cache('canonical'))

	::	og('url', basic_template_cache('canonical'))
	::	og('type', 'website')
	::	og('site_name', 'PHP JS CSS Web Toolkit App')
; ?>