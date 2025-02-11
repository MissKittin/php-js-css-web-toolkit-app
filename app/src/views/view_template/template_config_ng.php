<?php
	php_debugbar::get_template_config($view);

	require APP_LIB.'/basic_template_config.php';
	basic_template_config($view, static::class)

//	::	csp('script-src', '\'sha256-something\'')

	::	lang('en')
	::	title('Page title')
	::	meta_description('Page description')
	::	meta_robots('index,follow')

	::	og('url', (empty($_SERVER['HTTPS']) ? 'http' : 'https').'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'])
	::	og('type', 'website')
	::	og('site_name', 'My Awesome Website')
//	::	og('image', (empty($_SERVER['HTTPS']) ? 'http' : 'https').'://'.$_SERVER['HTTP_HOST'].app_template::get_public_dir_url().'/assets/website-logo.jpg')
//	::	og('image:type', 'image/jpeg')
//	::	og('image:width', '400')
//	::	og('image:height', '300')
//	::	og('image:alt', 'Red Bone')
//	::	og('locale:alternate', 'fr_FR')

//	::	meta_name('name', 'value')
//	::	meta_property('property', 'value')
//	::	html_headers('<my-header tag>')
//	::	favicon(__DIR__.'/favicon.html')

//	::	styles(app_template::get_public_dir_url().'/assets/style.css') // or styles('https://another.server/style.css', 'sha384-hash', 'anonymous')
//	::	scripts(app_template::get_public_dir_url().'/assets/script.js') // or scripts('https://another.server/script.js', 'sha384-hash', 'anonymous')
//	::	modules(app_template::get_public_dir_url().'/assets/module.js') // or modules('https://another.server/module.js', 'sha384-hash', 'anonymous')
//	::	scripts_top(app_template::get_public_dir_url().'/assets/script.js') // or scripts_top('https://another.server/script.js', 'sha384-hash', 'anonymous')
//	::	scripts_top_op(app_template::get_public_dir_url().'/assets/script.js', 'defer') // or scripts_top('https://another.server/script.js', 'defer', 'sha384-hash', 'anonymous')
//	::	modules_top(app_template::get_public_dir_url().'/assets/module.js') // or modules_top('https://another.server/module.js', 'sha384-hash', 'anonymous')

	// Bootstrap integration - cdn.jsdelivr.net
//	::	csp('style-src', 'https://cdn.jsdelivr.net')
//	::	csp('script-src', 'https://cdn.jsdelivr.net')

//	::	disable_default_styles()
//	::	disable_default_scripts()

//	::	styles('https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css', 'sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65', 'anonymous')
//	::	scripts('https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js', 'sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4', 'anonymous')

	// Bootstrap integration - local assets
//	::	disable_default_styles()
//	::	disable_default_scripts()

//	::	styles(app_template::get_public_dir_url().'/assets/bootstrap.min.css')
//	::	scripts(app_template::get_public_dir_url().'/assets/bootstrap.bundle.min.js')

	::	my_function(function($text){
			return '<span style="color: red;">'.$text.'</span>';
		})

	::	my_variable('my variables value')
; ?>