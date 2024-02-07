<?php
	if(!isset($_SERVER['HTTP_HOST']))
	{
		require APP_CTRL.'/samples/http_error.php';
		http_error(400);
		exit();
	}

	require APP_CTRL.'/samples/robots-sitemap.php';

	sitemap([
		'/about',
		'/check-date',
		'/database-test',
		'/obsfucate-html',
		'/login-library-test',
		'/phar-test',
		'/preprocessing-test'
	]);
?>