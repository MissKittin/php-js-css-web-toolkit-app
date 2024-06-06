<?php
	require APP_LIB.'/samples/app_template.php';
	require APP_LIB.'/samples/ob_adapter.php';

	ob_adapter
	::	add(new ob_adapter_obminifier())
	->	add(new ob_adapter_obsfucator('Title from routes/obsfucate-html.php'))
	->	add(new ob_adapter_gzip())
	->	add(new ob_adapter_filecache_mod('obsfucate-html.cache'))
	->	add(new ob_adapter_gunzip())
	->	start();

	app_template::quick_view('obsfucate-html', 'page_content.html');
?>