<?php
	require APP_LIB.'/app_template.php';
	require APP_LIB.'/samples/ob_cache.php';
	require TK_LIB.'/generate_csp_hash.php';

	if(ob_cache(ob_url2file(), 0))
		return;

	app_template::quick_view('samples/about');
?>