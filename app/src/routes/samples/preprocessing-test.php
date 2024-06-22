<?php
	require APP_LIB.'/app_template.php';

	// will be refreshed hourly ("Cache file was created" will disappear in an hour)
	require APP_LIB.'/samples/ob_cache.php';
	ob_cache(ob_url2file(), 3600);

	require APP_CTRL.'/samples/preprocessing-test.php';

	preprocessed_cache
	::	main(new app_template())
	->	view('samples/preprocessing-test');
?>