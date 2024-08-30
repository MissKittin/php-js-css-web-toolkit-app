<?php
	require APP_LIB.'/app_template.php';
	require APP_LIB.'/samples/ob_cache.php';

	if(ob_cache(ob_url2file(), 0))
		return;

	app_template::quick_view('samples/ws-test');
?>