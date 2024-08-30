<?php
	require APP_LIB.'/app_template.php';
	require APP_LIB.'/samples/ob_cache.php';

	if(ob_cache(ob_url2file(), 3600))
		return;

	$view=new app_template();

	require APP_CTRL.'/samples/check-date.php';

	$view->view('samples/check-date');
?>