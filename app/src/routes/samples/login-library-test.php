<?php
	require APP_LIB.'/samples/app_template.php';

	if(
		isset($_SERVER['HTTP_ACCEPT_ENCODING']) &&
		str_contains($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')
	)
		ob_start('ob_gzhandler');

	require APP_LIB.'/samples/session_start.php';

	$view=new app_template();

	require APP_MODEL.'/samples/login_library_test_credentials.php'; // import credentials and callback_function()
	require APP_CTRL.'/samples/login-library-test.php';

	$view->view('login-library-test');
?>