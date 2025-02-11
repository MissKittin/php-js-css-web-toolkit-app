<?php
	require APP_LIB.'/samples/app_template_inline.php';

	if(
		isset($_SERVER['HTTP_ACCEPT_ENCODING']) &&
		str_contains($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')
	)
		ob_start('ob_gzhandler');

	require APP_LIB.'/samples/app_session.php';

	app_session();

	require TK_LIB.'/check_var.php';
	require TK_LIB.'/sec_csrf.php';
	require APP_LIB.'/samples/app_setup_login_library.php'; // includes sec_login.php library

	app_setup_login_library();

	$view=new app_template_inline();

	require APP_MODEL.'/samples/login_library_test_credentials.php'; // import credentials and callback_function()

	if(require APP_CTRL.'/samples/login-library-test.php')
		return;

	$view->view('login-library-test');
?>