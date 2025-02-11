<?php
	php_debugbar::get_template_config($view);

	require APP_LIB.'/basic_template_config.php';
	basic_template_config($view, static::class)

	::	csp('img-src', 'data:')
	::	csp('style-src', '\'sha256-7RVXSGMKV/qUEAumoIuxc24YyJuEx0WkvzWG3GCeTvQ=\'')
	::	csp('style-src', '\'sha256-CCRU8y0C0FCtxUKmrcBZEZ9CMybYX9owafow9yrdZ0Y=\'') // minified
	::	csp('script-src', '\'sha256-uCg5tPeV67lfNiSPnik0QmQQKUbUxYwTJfPcUwHiIGI=\'')
	::	csp('script-src', '\'sha256-3d2JZ/dMNaC1GGU9wFbLRTG8Ck7QNv7Y9s4/YQgXZkw=\'') // minified

	::	lang('en_US')
	::	title('Main page')
	::	meta_description('PHP-JS-CSS web toolkit - a set of tools and libraries that you can use in your project')
	::	meta_robots('index,follow')
	::	scripts(app_template::get_public_dir_url().'/assets/sendNotification.js')

	::	og('url', (empty($_SERVER['HTTPS']) ? 'http' : 'https').'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'])
	::	og('type', 'website')
	::	og('site_name', 'PHP JS CSS Web Toolkit App')

	::	home_links([
		'About toolkit'=>'/about',
		'Bootstrap framework test'=>'/bootstrap-test',
		'check_date() test'=>'/check-date',
		'Database libraries test'=>'/database-test',
		'HTML obsfucator test'=>'/obsfucate-html',
		'Login library test'=>'/login-library-test',
		'Login component test (login and password: test)'=>'/login-component-test',
		'multipage.js test'=>'/multipage-test',
		'Toolkit in Phar test'=>'/phar-test',
		'PHP preprocessing test'=>'/preprocessing-test',
		'Websockets test'=>'/ws-test',
		'Toolkit test'=>'/tk-test',
		'HTTP errors'=>[
			'400'=>'/http-error-test',
			'401'=>'/http-error-test/401',
			'403'=>'/http-error-test/403',
			'404'=>'/nonexistent',
			'410'=>'/http-error-test/410'
		]
	])
; ?>