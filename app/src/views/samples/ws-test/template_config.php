<?php
	php_debugbar::get_template_config($view);

	require APP_LIB.'/basic_template_config.php';
	basic_template_config($view, static::class)

	::	csp('style-src', '\'unsafe-inline\'')
	::	csp('script-src', '\'sha256-/X5PLF1RFAQnQE1exIRAG0cmaQJ4A4miMM25uzsWk6o=\'')
	::	csp('connect-src', '*')

	::	lang('en_US')
	::	title('Websockets')
	::	meta_description('Websockets test')
	::	meta_robots('noindex,nofollow')

	::	og('url', (empty($_SERVER['HTTPS']) ? 'http' : 'https').'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'])
	::	og('type', 'website')
	::	og('site_name', 'PHP JS CSS Web Toolkit App')
; ?>