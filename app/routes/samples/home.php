<?php
	require APP_LIB.'/samples/default_http_headers.php';

	require APP_LIB.'/samples/ob_adapter.php';
	ob_adapter
		::add(new ob_adapter_obminifier())
		->add(new ob_adapter_gzip())
		->add(new ob_adapter_filecache_mod('home.cache'))
		->add(new ob_adapter_gunzip())
		->start();

	require APP_LIB.'/samples/app_template.php';
	app_template::quick_view('home');
?>