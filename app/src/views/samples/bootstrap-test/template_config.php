<?php
	php_debugbar::get_template_config($view);

	require APP_LIB.'/basic_template_config.php';
	basic_template_config($view, static::class)

	::	csp('style-src', generate_csp_hash_file(__DIR__.'/style.css'))

	::	lang('en_US')
	::	title('Bootstrap test')
	::	meta_description('Bootstrap framework test')
	::	meta_robots('index,follow')

	::	disable_default_styles()
	::	disable_default_scripts();

	if(file_exists(APP_ROOT.'/public/assets/bootstrap.min.css'))
	{
		log_infos()->info(
			'views/bootstrap-test: using local bootstrap.min.css'
		);

		basic_template_config::styles(''
		.	app_template::get_public_dir_url()
		.	'/assets/bootstrap.min.css'
		);
	}
	else
	{
		log_infos()->info(
			'views/bootstrap-test: using bootstrap.min.css from cdn.jsdelivr.net'
		);

		basic_template_config
		::	csp('style-src', 'https://cdn.jsdelivr.net')
		::	styles(
				'https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css',
				'sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65',
				'anonymous'
			);
	}

	if(file_exists(APP_ROOT.'/public/assets/bootstrap.bundle.min.js'))
	{
		log_infos()->info(
			'views/bootstrap-test: using local bootstrap.bundle.min.js'
		);

		basic_template_config::scripts(''
		.	app_template::get_public_dir_url()
		.	'/assets/bootstrap.bundle.min.js'
		);
	}
	else
	{
		log_infos()->info(
			'views/bootstrap-test: using bootstrap.bundle.min.js from cdn.jsdelivr.net'
		);

		basic_template_config
		::	csp('script-src', 'https://cdn.jsdelivr.net')
		::	scripts(
				'https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js',
				'sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4',
				'anonymous'
			);
	}

	basic_template_config
	::	og('url', (empty($_SERVER['HTTPS']) ? 'http' : 'https').'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'])
	::	og('type', 'website')
	::	og('site_name', 'PHP JS CSS Web Toolkit App')
; ?>