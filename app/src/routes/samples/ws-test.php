<?php
	require APP_LIB.'/samples/app_template_inline.php';
	require APP_LIB.'/samples/ob_cache.php';

	if(ob_cache(ob_url2file(), 0))
	{
		app_template_inline::finish_request();
		return;
	}

	app_template_inline::quick_view('ws-test');
?>