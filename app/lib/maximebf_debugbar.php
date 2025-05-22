<?php
	/*
	 * Facade for Maxime Bouroumeau-Fuseau's DebugBar
	 * Extension for basic_template component
	 *
	 * WARNING!!!
	 *  NEVER EVER ENABLE THIS LIBRARY ON PRODUCTION!!!
	 *
	 * Warning:
	 *  maximebf/debugbar or php-debugbar/php-debugbar package is recommended
	 *
	 * Note:
	 *  read the maximebf_debugbar.php (toolkit) library documentation first
	 *  throws an maximebf_debugbar_exception on error
	 *
	 * Quick start (with app_params.php library):
		if(php_debugbar
		::	enable((getenv('APP_ENV') === 'dev'))
		::	set_vendor_dir('./vendor') // must be after enable()
		::	set_vendor_dir('phar://./vendor.phar/vendor') // you can hit and miss, the one above has priority if hit
		::	collectors([ // optional
				'pdo'=>(class_exists('\DebugBar\DataCollector\PDO\PDOCollector')) ? new DebugBar\DataCollector\PDO\PDOCollector() : new php_debugbar_dummy()
			])
		::	set_csp_nonce('phpdebugbar') // optional
		::	set_base_url('/__PHPDEBUGBAR__')
		::	route('/'.app_params())
			exit();
	 *
	 * Injecting debug bar code into the page:
	 *  CSP rules must be added to run the extension
	 *  in the view's template_config.php add at the beginning:
		php_debugbar::get_template_config($view);
	 *  at the end of the page_content.php add:
		<?php php_debugbar::get_page_content(); ?>
	 */

	if(!class_exists('maximebf_debugbar'))
		require TK_LIB.'/maximebf_debugbar.php';

	class php_debugbar extends maximebf_debugbar
	{
		public static function get_template_config(&$view)
		{
			if(!static::$enabled)
				return;

			foreach(static::get_csp_headers() as $section=>$values)
				foreach($values as $value)
					$view['_csp_header'][$section][]=$value;

			if(!isset($view['_html_headers']))
				$view['_html_headers']='';

			$view['_html_headers'].=static::get_html_headers();
		}
		public static function get_page_content()
		{
			echo parent::{__FUNCTION__}();
		}
	}
	class php_debugbar_dummy extends maximebf_debugbar_dummy {}
?>