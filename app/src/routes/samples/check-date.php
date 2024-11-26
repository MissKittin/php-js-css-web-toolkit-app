<?php
	require APP_LIB.'/samples/app_template_inline.php';
	require APP_LIB.'/samples/ob_cache.php';

	if(ob_cache(ob_url2file(), 3600))
	{
		app_template_inline::finish_request();
		return;
	}

	$view=new app_template_inline();

	require APP_CTRL.'/samples/check-date.php';

	$view->view('check-date');
?>