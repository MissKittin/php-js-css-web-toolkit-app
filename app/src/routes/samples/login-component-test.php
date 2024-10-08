<?php
	// enable logging
	const LOGGER_APP_NAME='login-component-test';
	require APP_LIB.'/samples/logger.php';

	require APP_LIB.'/app_template.php';

	if(
		isset($_SERVER['HTTP_ACCEPT_ENCODING']) &&
		str_contains($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')
	)
		ob_start('ob_gzhandler');

	require APP_LIB.'/samples/app_session.php';
	app_session();

	require TK_COM.'/login/main.php';

	// add bruteforce protection
	require APP_LIB.'/samples/pdo_instance.php';
	require TK_LIB.'/sec_bruteforce.php';

	// set custom session reloader
	if(class_exists('lv_cookie_session_handler'))
		login_com_reg_config::_()['session_reload']=function($lifetime)
		{
			lv_cookie_session_handler::session_start([
				'cookie_lifetime'=>$lifetime
			]);
		};

	require APP_MODEL.'/samples/login_component_test_credentials.php';
	$model='login_component_test_credentials';

	if(require APP_CTRL.'/samples/login-component-test.php')
		return;

	if(is_logged())
		app_template::quick_view('samples/login-component-test');
?>