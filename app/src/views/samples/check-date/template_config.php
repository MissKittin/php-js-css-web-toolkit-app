<?php
	php_debugbar::get_template_config($view);

	require APP_LIB.'/basic_template_config.php';
	basic_template_config($view, static::class)

	::	csp('style-src', '\'unsafe-hashes\'')
	::	csp('style-src', '\'sha256-Ls1729j3r2TF/b4LA4PWZuspbwvcg6xE5uxyBKBeXrI=\'')
	::	csp('style-src', '\'sha256-LpG2EGpPl462t81FieLddfap21+YrF49ronG6DL+7A8=\'')

	::	lang('en_US')
	::	title('Check date test')
	::	meta_description('PHP library test')
	::	meta_robots('index,follow')

	::	og('url', (empty($_SERVER['HTTPS']) ? 'http' : 'https').'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'])
	::	og('type', 'website')
	::	og('site_name', 'PHP JS CSS Web Toolkit App')
; ?>