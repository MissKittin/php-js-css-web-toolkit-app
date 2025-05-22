<?php
	if(app_params_explode(1) === 'api')
	{
		echo json_encode([
			'text'=>'This string comes from API',
			'number'=>rand(0, 10)
		], JSON_UNESCAPED_UNICODE);

		return;
	}

	require APP_LIB.'/samples/app_template_inline.php';
	require APP_LIB.'/samples/ob_cache.php';

	if(ob_cache(ob_url2file(), 0))
	{
		app_template_inline::finish_request();
		return;
	}

	require TK_LIB.'/generate_csp_hash.php';

	if(
		defined('TKE_LIB') &&
		file_exists(TKE_LIB.'/lorem_ipsum_generator.php')
	)
		require TKE_LIB.'/lorem_ipsum_generator.php';
	else
	{
		function generate_lorem_ipsum_wp()
		{
			echo '<h3>Install toolkit extras to generate some lipsum</h3>';
		}
	}

	app_template_inline::quick_view('multipage-test');
?>