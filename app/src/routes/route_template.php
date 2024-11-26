<?php
	//namespace app\src\routes;

	//use app_template;
	//use ob_adapter;
	//use ob_adapter_obminifier;
	//use ob_adapter_obsfucator;
	//use ob_adapter_gzip;
	//use ob_adapter_filecache;
	//use ob_adapter_gunzip;
	//use ob_cache;
	//use ob_cache_predis_silent;
	//use ob_cache_redis;
	//use ob_cache_memcached;
	//use ob_cache_file;
	//use pdo_instance;
	//use app\src\controllers\controller_template;
	//use app\src\models\pdo_model_template;
	//use app\src\models\cache_model_template;

	require APP_LIB.'/app_template.php';
	//require APP_LIB.'/ob_adapter.php';

	//if(ob_adapter
	//::	add(new ob_adapter_obminifier())
	//::	add(new ob_adapter_obsfucator('Page title', 'Enable js'))
	//::	add(new ob_adapter_gzip())
	//::	add(new ob_adapter_filecache(VAR_CACHE.'/ob_adapter/'.$output_file))
	//::	add(new ob_adapter_gunzip())
	//::	start()){
	//	app_template::finish_request();
	//	return;
	//}

	//require APP_LIB.'/ob_cache.php';
	//require TK_LIB.'/ob_cache.php';
	//require TK_LIB.'/predis_connect.php';
	//require TK_LIB.'/redis_connect.php';
	//require TK_LIB.'/memcached_connect.php';

	//if(ob_cache
	//::	url(ob_url2file())
	//::	expire(3600) // optional, 0 -> permanent cache
	//::	add(new ob_cache_predis_silent(
	//		predis_connect_proxy(APP_DB.'/predis')
	//	))
	//::	add(new ob_cache_redis(
	//		redis_connect(APP_DB.'/redis')
	//	))
	//::	add(new ob_cache_memcached(
	//		memcached_connect(APP_DB.'/memcached')
	//	))
	//::	add(new ob_cache_file(
	//		VAR_CACHE.'/ob_cache'
	//	))
	//::	start()){
	//	app_template::finish_request();
	//	return;
	//}

	//if(
	//	isset($_SERVER['HTTP_ACCEPT_ENCODING']) &&
	//	str_contains($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')
	//)
	//	ob_start('ob_gzhandler');

	//if(!app_session
	//::	add(new app_session_mod_cookie(
	//		app_env('APP_SESSION_COOKIE_KEY', null)
	//	))
	//::	add(new app_session_mod_files())
	//::	session_start())
	//	throw new app_exception(
	//		'Session cannot be started'
	//	);

	//require APP_LIB.'/setup_login_library.php';
	//setup_login_library(app_env('APP_PASSWD_HASH'));

	// Note: move this block to new library in app/lib
	//require APP_LIB.'/pdo_instance.php';
	//pdo_instance
	//::	set_default_db('sqlite')
	//::	enable_exceptions()
	//::	enable_seeder();

	require APP_CTRL.'/controller_template.php';
	require APP_MODEL.'/pdo_model_template.php';
	require APP_MODEL.'/cache_model_template.php';

	controller_template::main(
		new pdo_model_template(
			controller_template::model_params()
		),
		new app_template(),
		new cache_model_template(
			controller_template::cache_params()
		)
	)->view('view_template');

	//app_template::quick_view('view_template');

	//app_template::quick_view(
	//	'view_template',
	//	'page_content.html'
	//);
?>