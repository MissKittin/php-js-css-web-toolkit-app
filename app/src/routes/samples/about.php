<?php
	require APP_LIB.'/samples/app_template.php';
	require APP_LIB.'/samples/ob_cache.php';

	ob_cache(ob_url2file(), 0);
	app_template::quick_view('about');
?>