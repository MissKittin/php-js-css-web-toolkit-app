<?php
	require APP_LIB.'/samples/app_template_inline.php';
	require TK_LIB.'/check_var.php';

	if(defined('TK_PHAR'))
	{
		if(
			isset($_SERVER['HTTP_ACCEPT_ENCODING']) &&
			str_contains($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')
		)
			ob_start('ob_gzhandler');

		app_template_inline::quick_view('tk-test', 'phar_not_allowed.html');

		return;
	}

	require APP_CTRL.'/samples/tk-test.php';

	$type=check_get_escaped('type');
	$name=check_get_escaped('name');

	if(($type !== null) && ($name !== null))
	{
		if(
			isset($_SERVER['HTTP_ACCEPT_ENCODING']) &&
			str_contains($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')
		)
			ob_start('ob_gzhandler');

		if(!run_test($type, $name))
			echo '<pre>You\'re up to something</pre>';

		return;
	}

	require APP_LIB.'/samples/ob_cache.php';

	if(ob_cache(ob_url2file(), 0))
	{
		app_template_inline::finish_request();
		return;
	}

	get_tests(new app_template_inline())
	->	view('tk-test');
?>