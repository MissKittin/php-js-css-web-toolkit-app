<?php
	require APP_LIB.'/app_template.php';

	if(!is_file('./tk.phar'))
	{
		app_template::quick_view('samples/phar-test', 'phar_not_found.php');
		exit();
	}

	$view=new app_template();

	require APP_CTRL.'/samples/phar-test.php';

	$view->view('samples/phar-test');
?>