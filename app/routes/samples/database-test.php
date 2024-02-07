<?php
	require APP_LIB.'/samples/default_http_headers.php';
	require APP_LIB.'/samples/app_template.php';

	if(
		isset($_SERVER['HTTP_ACCEPT_ENCODING']) &&
		str_contains($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')
	)
		ob_start('ob_gzhandler');

	require APP_LIB.'/samples/session_start.php';

	require APP_MOD.'/samples/database_test_model.php';
	require APP_CTRL.'/samples/database-test.php';

	database_test_controller::main(
		new database_test_model(
			database_test_controller::model_params()
		),
		new app_template()
	)->view('database-test');
?>