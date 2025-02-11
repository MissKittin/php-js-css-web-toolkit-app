<?php
	if(!isset($_SERVER['HTTP_HOST']))
	{
		require APP_CTRL.'/samples/http_error.php';

		http_error(400);
		return;
	}

	if(
		isset($_SERVER['HTTP_ACCEPT_ENCODING']) &&
		str_contains($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')
	)
		ob_start('ob_gzhandler');

	require APP_CTRL.'/samples/robots-sitemap.php';

	sitemap([
		'/',
		'/about',
		'/bootstrap-test',
		'/check-date',
		'/database-test',
		'/login-library-test',
		'/multipage-test',
		'/obsfucate-html',
		'/phar-test',
		'/preprocessing-test'
	]);

	if(function_exists('fastcgi_finish_request'))
		fastcgi_finish_request();
?>