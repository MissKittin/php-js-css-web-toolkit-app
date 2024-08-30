<?php
	require APP_LIB.'/app_template.php';
	require APP_LIB.'/samples/ob_adapter.php';

	if(ob_adapter
	::	add(new ob_adapter_obminifier())
	::	add(new ob_adapter_gzip())
	::	add(new ob_adapter_filecache_mod('home.cache', 'ob_adapter'))
	::	add(new ob_adapter_gunzip())
	::	start())
		return;

	app_template::quick_view('samples/home');
?>