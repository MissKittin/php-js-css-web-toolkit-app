<?php
	require APP_LIB.'/app_template.php';
	require APP_LIB.'/samples/ob_adapter.php';

	if(ob_adapter
	::	add(new ob_adapter_obminifier())
	::	add(new ob_adapter_obsfucator('Title from routes/obsfucate-html.php'))
	::	add(new ob_adapter_gzip())
	::	add(new ob_adapter_filecache_mod('obsfucate-html.cache', 'ob_adapter'))
	::	add(new ob_adapter_gunzip())
	::	start())
		return;

	app_template::quick_view('samples/obsfucate-html', 'page_content.html');
?>