<?php
	require APP_LIB.'/samples/default_http_headers.php';

	require APP_LIB.'/samples/ob_cache.php';
	ob_cache(ob_url2file(), 0);

	require APP_LIB.'/samples/app_template.php';
	app_template::quick_view('ws-test');
?>