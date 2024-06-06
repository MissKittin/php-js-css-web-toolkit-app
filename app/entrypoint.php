<?php
	require __DIR__.'/lib/stdlib.php';
	require TK_COM.'/php_polyfill/main.php';

	chdir(APP_ROOT);

	if(file_exists(APP_ROOT.'/composer.json'))
		require APP_ROOT.'/vendor/autoload.php';

	if(
		(!isset($_SERVER['REQUEST_URI'])) ||
		(!isset($_SERVER['REQUEST_METHOD']))
	){
		require APP_CTRL.'/samples/http_error.php';
		http_error(400);
		exit();
	}

	if(
		($_SERVER['REQUEST_METHOD'] !== 'GET') &&
		($_SERVER['REQUEST_METHOD'] !== 'POST')
	){
		require APP_CTRL.'/samples/http_error.php';
		http_error(400);
		exit();
	}

	/*
	 * The following headers are ignored by public/.htaccess
	 * Edit public/.htaccess to get the following code to work
	 */
	if(
		isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&
		($_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')
	)
		$_SERVER['HTTPS']='on';
	if(isset($_SERVER['HTTP_X_FORWARDED_HOST']))
		$_SERVER['HTTP_HOST']=$_SERVER['HTTP_X_FORWARDED_HOST'];
	if(isset($_SERVER['HTTP_X_FORWARDED_PORT']))
		$_SERVER['SERVER_PORT']=$_SERVER['HTTP_X_FORWARDED_PORT'];
	if(isset($_SERVER['HTTP_X_REAL_IP']))
		$_SERVER['REMOTE_ADDR']=$_SERVER['HTTP_X_REAL_IP'];

	register_shutdown_function(function(){
		$exec_time=microtime(true)-$_SERVER['REQUEST_TIME_FLOAT'];
		error_log('Executed in '.$exec_time.' seconds, '.memory_get_peak_usage().' bytes used, '.count(get_included_files()).' scripts included');
	});

	switch(explode('/', strtok($_SERVER['REQUEST_URI'], '?'))[1])
	{
		case '': require APP_ROUT.'/samples/home.php'; break;

		case 'about': require APP_ROUT.'/samples/about.php'; break;
		case 'check-date': require APP_ROUT.'/samples/check-date.php'; break;
		case 'database-test': require APP_ROUT.'/samples/database-test.php'; break;
		case 'obsfucate-html': require APP_ROUT.'/samples/obsfucate-html.php'; break;
		case 'login-library-test': require APP_ROUT.'/samples/login-library-test.php'; break;
		case 'login-component-test': require APP_ROUT.'/samples/login-component-test.php'; break;
		case 'phar-test': require APP_ROUT.'/samples/phar-test.php'; break;
		case 'preprocessing-test': require APP_ROUT.'/samples/preprocessing-test.php'; break;
		case 'http-error-test': require APP_ROUT.'/samples/http-error-test.php'; break;
		case 'tk-test': require APP_ROUT.'/samples/tk-test.php'; break;
		case 'ws-test': require APP_ROUT.'/samples/ws-test.php'; break;

		case 'robots.txt': require APP_ROUT.'/samples/robots.php'; break;
		case 'sitemap.xml': require APP_ROUT.'/samples/sitemap.php'; break;

		default:
			require APP_CTRL.'/samples/http_error.php';

			if(is_dir($_SERVER['DOCUMENT_ROOT'].strtok($_SERVER['REQUEST_URI'], '?')))
				require http_error(403);
			else
				require http_error(404);
	}

	error_log(basename(__FILE__).' finished');
?>