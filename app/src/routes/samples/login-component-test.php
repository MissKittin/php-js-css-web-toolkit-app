<?php
	// enable logging
		const LOGGER_APP_NAME='login-component-test';
		require APP_LIB.'/samples/logger.php';

	require APP_LIB.'/samples/app_template_inline.php';

	if(
		isset($_SERVER['HTTP_ACCEPT_ENCODING']) &&
		str_contains($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')
	)
		ob_start('ob_gzhandler');

	require APP_LIB.'/samples/app_session.php';
	app_session();

	require TK_COM.'/login/main.php';
	require APP_LIB.'/samples/app_setup_login_library.php';
	app_setup_login_library();

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
	$model::add_login_validator_rules();
	login_component_test_session_manager::add_login_validator_rules();

	if(require APP_CTRL.'/samples/login-component-test.php')
	{
		app_template_inline::finish_request();
		return;
	}

	app_template_inline::quick_view('login-component-test');
?>