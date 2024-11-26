<?php
	require APP_LIB.'/samples/app_template_inline.php';

	if(
		isset($_SERVER['HTTP_ACCEPT_ENCODING']) &&
		str_contains($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')
	)
		ob_start('ob_gzhandler');

	if(!is_file('./tk.phar'))
	{
		app_template_inline::quick_view('phar-test', 'phar_not_found.php');
		return;
	}

	$view=new app_template_inline();

	require APP_CTRL.'/samples/phar-test.php';

	$view->view('phar-test');
?>