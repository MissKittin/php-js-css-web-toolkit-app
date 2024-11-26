<?php
	if(file_exists(__DIR__.'/../var/APP_IS_DOWN'))
	{
		readfile(__DIR__.'/../var/APP_IS_DOWN');
		return false;
	}

	require __DIR__.'/lib/stdlib.php';

	if(php_sapi_name() === 'cli-server')
		require TK_LIB.'/cli_server_finish_request.php';

	if(file_exists(APP_DIR.'/php_polyfill.php'))
		require APP_DIR.'/php_polyfill.php';
	else
		require TK_COM.'/php_polyfill/main.php';

	chdir(APP_ROOT);

	if(file_exists(APP_ROOT.'/composer.json'))
		require APP_ROOT.'/vendor/autoload.php';

	if(
		(!isset($_SERVER['REQUEST_URI'])) ||
		(!isset($_SERVER['REQUEST_METHOD']))
	){
		require APP_CTRL.'/http_error.php';
		http_error(400);
		return false;
	}

	if(
		($_SERVER['REQUEST_METHOD'] !== 'GET') &&
		($_SERVER['REQUEST_METHOD'] !== 'POST')
	){
		require APP_CTRL.'/http_error.php';
		http_error(400);
		return false;
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

	require APP_LIB.'/app_params.php';
	//require TK_LIB.'/uri_router.php';
	//require TK_COM.'/superclosure_router/main.php';

	//uri_router
	//::	set_source(app_params())
	//::	set_default_route(function(){
	//		require APP_CTRL.'/http_error.php';

	//		if(is_dir($_SERVER['DOCUMENT_ROOT'].strtok($_SERVER['REQUEST_URI'], '?')))
	//		{
	//			http_error(403);
	//			return;
	//		}

	//		http_error(404);
	//	})
	//::	add_file([''], APP_ROUTE.'/route_template.php')
	//::	add(['route'], function(){
	//		//
	//	})
	//::	add(['route-b', 'route-b/(01|02|03|10)'], function($matches){
	//		// $matches[1] can be not set, 01, 02, 03 or 10
	//	}, true)
	//::	route();

	//if(file_exists(VAR_CACHE.'/superclosure_router_cache.php'))
	//	require VAR_CACHE.'/superclosure_router_cache.php';
	//else
	//	superclosure_router
	//	::	set_source(app_params())
	//	::	set_default_route(function(){
	//			require APP_CTRL.'/http_error.php';

	//			if(is_dir($_SERVER['DOCUMENT_ROOT'].strtok($_SERVER['REQUEST_URI'], '?')))
	//			{
	//				http_error(403);
	//				return;
	//			}

	//			http_error(404);
	//		})
	//	::	add_file([''], APP_ROUTE.'/route_template.php')
	//	::	add(['route'], function(){
	//			//
	//		})
	//	::	add(['route-b', 'route-b/(01|02|03|10)'], function($matches){
	//			// $matches[1] can be not set, 01, 02, 03 or 10
	//		}, true)

	//	::	set_source_variable('app_params()')
	//	::	set_request_method_variable("\$_SERVER['REQUEST_METHOD']")
	//	::	dump_cache(VAR_CACHE.'/superclosure_router_cache.php')
	//	::	route();

	switch(app_params_explode(0))
	{
		case '': require APP_ROUTE.'/samples/home.php'; break;

		case 'about': require APP_ROUTE.'/samples/about.php'; break;
		case 'bootstrap-test': require APP_ROUTE.'/samples/bootstrap-test.php'; break;
		case 'check-date': require APP_ROUTE.'/samples/check-date.php'; break;
		case 'database-test': require APP_ROUTE.'/samples/database-test.php'; break;
		case 'obsfucate-html': require APP_ROUTE.'/samples/obsfucate-html.php'; break;
		case 'login-library-test': require APP_ROUTE.'/samples/login-library-test.php'; break;
		case 'login-component-test': require APP_ROUTE.'/samples/login-component-test.php'; break;
		case 'phar-test': require APP_ROUTE.'/samples/phar-test.php'; break;
		case 'preprocessing-test': require APP_ROUTE.'/samples/preprocessing-test.php'; break;
		case 'http-error-test': require APP_ROUTE.'/samples/http-error-test.php'; break;
		case 'tk-test': require APP_ROUTE.'/samples/tk-test.php'; break;
		case 'ws-test': require APP_ROUTE.'/samples/ws-test.php'; break;

		case 'robots.txt': require APP_ROUTE.'/samples/robots.php'; break;
		case 'sitemap.xml': require APP_ROUTE.'/samples/sitemap.php'; break;

		default:
			require APP_CTRL.'/http_error.php';

			if(is_dir($_SERVER['DOCUMENT_ROOT'].strtok($_SERVER['REQUEST_URI'], '?')))
			{
				http_error(403);
				break;
			}

			http_error(404);
	}

	error_log(basename(__FILE__).' finished');
?>