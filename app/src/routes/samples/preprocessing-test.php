<?php
	require APP_LIB.'/samples/app_template_inline.php';

	// will be refreshed hourly ("Cache file was created" will disappear in an hour)
		require APP_LIB.'/samples/ob_cache.php';

		if(ob_cache(ob_url2file(), 3600))
		{
			app_template_inline::finish_request();
			return;
		}

	require APP_CTRL.'/samples/preprocessing-test.php';

	preprocessed_cache
	::	main(new app_template_inline())
	->	view('preprocessing-test');
?>