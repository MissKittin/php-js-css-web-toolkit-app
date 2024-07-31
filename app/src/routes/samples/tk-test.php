<?php
	require APP_LIB.'/app_template.php';
	require TK_LIB.'/check_var.php';

	if(defined('TK_PHAR'))
	{
		app_template::quick_view('samples/tk-test', 'phar_not_allowed.html');
		exit();
	}

	require APP_CTRL.'/samples/tk-test.php';

	$type=check_get_escaped('type');
	$name=check_get_escaped('name');

	if(($type !== null) && ($name !== null))
	{
		if(!run_test($type, $name))
			echo '<pre>You\'re up to something</pre>';
	}
	else
	{
		require APP_LIB.'/samples/ob_cache.php';
		ob_cache(ob_url2file(), 0);

		get_tests(new app_template())->view('samples/tk-test');
	}
?>