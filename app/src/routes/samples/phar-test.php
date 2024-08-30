<?php
	require APP_LIB.'/app_template.php';

	if(
		isset($_SERVER['HTTP_ACCEPT_ENCODING']) &&
		str_contains($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')
	)
		ob_start('ob_gzhandler');

	if(!is_file('./tk.phar'))
	{
		app_template::quick_view('samples/phar-test', 'phar_not_found.php');
		return;
	}

	$view=new app_template();

	require APP_CTRL.'/samples/phar-test.php';

	$view->view('samples/phar-test');
?>