<?php
	$view['csp_header']['img-src'][]='data:';
	$view['csp_header']['style-src'][]='\'sha256-7RVXSGMKV/qUEAumoIuxc24YyJuEx0WkvzWG3GCeTvQ=\'';
	$view['csp_header']['style-src'][]='\'sha256-CCRU8y0C0FCtxUKmrcBZEZ9CMybYX9owafow9yrdZ0Y=\''; // minified
	$view['csp_header']['script-src'][]='\'sha256-uCg5tPeV67lfNiSPnik0QmQQKUbUxYwTJfPcUwHiIGI=\'';
	$view['csp_header']['script-src'][]='\'sha256-3d2JZ/dMNaC1GGU9wFbLRTG8Ck7QNv7Y9s4/YQgXZkw=\''; // minified

	$view['lang']='en';
	$view['title']='Main page';
	$view['meta_description']='PHP-JS-CSS web toolkit - a set of tools and libraries that you can use in your project';
	$view['meta_robots']='index,follow';
	$view['scripts']=[['/assets/sendNotification.js', null, null]];
	$view['home_links']=[
		'About toolkit'=>'/about',
		'check_date() test'=>'/check-date',
		'Database libraries test'=>'/database-test',
		'HTML obsfucator test'=>'/obsfucate-html',
		'Login library test'=>'/login-library-test',
		'Login component test (login and password: test)'=>'/login-component-test',
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
	];
?>