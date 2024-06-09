<?php
	require APP_LIB.'/samples/app_template.php';

	if(!is_file('./tk.phar'))
	{
		app_template::quick_view('phar-test', 'phar_not_found.php');
		exit();
	}

	$view=new app_template();

	require APP_CTRL.'/samples/phar-test.php';

	$view->view('phar-test');
?>