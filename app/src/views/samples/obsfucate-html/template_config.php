<?php
	require APP_LIB.'/basic_template_config.php';
	basic_template_config($view, static::class)

	::	lang('en_US')
	::	title('HTML obsfucator')
	::	meta_description('Output buffer library test')
	::	meta_robots('index,follow')

	::	og('url', (empty($_SERVER['HTTPS']) ? 'http' : 'https').'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'])
	::	og('type', 'website')
	::	og('site_name', 'PHP JS CSS Web Toolkit App')
; ?>