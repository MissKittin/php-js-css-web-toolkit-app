<?php
	require APP_LIB.'/samples/default_http_headers.php';

	if(
		isset($_SERVER['HTTP_ACCEPT_ENCODING']) &&
		str_contains($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')
	)
		ob_start('ob_gzhandler');

	require APP_LIB.'/samples/session_start.php';
	// set custom session reloader
	if(class_exists('lv_cookie_session_handler'))
		configure_login_component([
			'config'=>[
				'session_reload'=>function($lifetime){
					lv_cookie_session_handler::session_start([
						'cookie_lifetime'=>$lifetime
					]);
				}
			]
		]);

	require APP_MOD.'/samples/login_component_test_credentials.php';
	require APP_CTRL.'/samples/login-component-test.php';

	if(is_logged()) // included by the login component in the controller
	{
		require APP_LIB.'/samples/app_template.php';
		app_template::quick_view('login-component-test');
	}
?>