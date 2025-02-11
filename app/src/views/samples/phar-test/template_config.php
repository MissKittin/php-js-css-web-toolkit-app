<?php
	php_debugbar::get_template_config($view);

	require APP_LIB.'/basic_template_config.php';
	basic_template_config($view, static::class)

	::	csp('style-src', '\'sha256-Cccgmc9inkViNhOVWZetVV760aISEwpy7qiKnRigEps=\'')

	::	lang('en_US')
	::	title('Phar test')
	::	meta_description('Use toolkit in Phar')
	::	meta_robots('index,follow')

	::	og('url', (empty($_SERVER['HTTPS']) ? 'http' : 'https').'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'])
	::	og('type', 'website')
	::	og('site_name', 'PHP JS CSS Web Toolkit App')
; ?>