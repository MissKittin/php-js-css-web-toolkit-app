<?php
	require APP_LIB.'/samples/default_http_headers.php';
	require APP_LIB.'/samples/app_template.php';

	if(!is_file('./tk.phar'))
	{
		default_template::quick_view(APP_VIEW.'/samples/phar-test', 'phar_not_found.php');
		exit();
	}

	$view=new app_template();

	require APP_CTRL.'/samples/phar-test.php';

	$view->view('phar-test');
?>