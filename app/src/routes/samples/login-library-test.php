<?php
	require APP_LIB.'/app_template.php';

	if(
		isset($_SERVER['HTTP_ACCEPT_ENCODING']) &&
		str_contains($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')
	)
		ob_start('ob_gzhandler');

	require APP_LIB.'/samples/app_session.php';
	app_session();

	$view=new app_template();

	require APP_MODEL.'/samples/login_library_test_credentials.php'; // import credentials and callback_function()

	if(require APP_CTRL.'/samples/login-library-test.php')
		return;

	$view->view('samples/login-library-test');
?>