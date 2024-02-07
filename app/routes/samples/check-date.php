<?php
	require APP_LIB.'/samples/default_http_headers.php';

	require APP_LIB.'/samples/ob_cache.php';
	ob_cache(ob_url2file(), 3600);

	require APP_LIB.'/samples/app_template.php';
	$view=new app_template();

	require APP_CTRL.'/samples/check-date.php';

	$view->view('check-date');
?>