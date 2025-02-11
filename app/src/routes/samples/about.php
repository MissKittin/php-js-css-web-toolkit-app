<?php
	require APP_LIB.'/samples/app_template_inline.php';
	require APP_LIB.'/samples/ob_cache.php';

	if(ob_cache(ob_url2file(), 0))
	{
		app_template_inline::finish_request();
		return;
	}

	require TK_LIB.'/generate_csp_hash.php';

	app_template_inline::quick_view('about');
?>