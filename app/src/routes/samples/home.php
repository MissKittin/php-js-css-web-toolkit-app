<?php
	require APP_LIB.'/samples/app_template_inline.php';
	require APP_LIB.'/samples/ob_adapter.php';

	if(ob_adapter
	::	add(new ob_adapter_obminifier())
	::	add(new ob_adapter_gzip())
	::	add(new ob_adapter_filecache_mod('home.cache', 'ob_adapter'))
	::	add(new ob_adapter_gunzip())
	::	start()){
		app_template_inline::finish_request();
		return;
	}

	app_template_inline::quick_view('home');
?>