<?php
	function app_root_path(string $on_empty_string='')
	{
		/*
		 * Print path/URL to application root directory
		 * Alternative to app_template::get_public_dir_url() from app_template.php library
		 *
		 * Usage:
			app_root_path() // returns e.g. '/myapp' or '' if the app is in the document root
			app_root_path('/') // returns e.g. '/myapp' or '/' if the app is in the document root
			app_root_path().'/'.app_params_explode(0) // returns e.g. '/myapp/param1' or '/param1' if the app is in the document root
		 *
		 * Required $_SERVER variables:
		 *  SCRIPT_NAME
		 */

		static $public_dir=null;

		if($public_dir === null)
			$public_dir=str_replace(
				'\\', '/',
				dirname($_SERVER['SCRIPT_NAME'])
			);

		if(
			($public_dir === '') ||
			($public_dir === '/')
		)
			return $on_empty_string;

		return $public_dir;
	}
?>