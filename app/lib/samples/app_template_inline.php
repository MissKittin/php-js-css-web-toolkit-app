<?php
	/*
	 * Rubber overlay that implements APP_INLINE_ASSETS support
	 * for the app_template.php library
	 */

	require APP_LIB.'/app_template.php';

	class app_template_inline extends app_template
	{
		public static function __callStatic($method, $args)
		{
			if(!class_exists('basic_template'))
				require APP_COM.'/basic_template/main.php';

			if(app_env('APP_INLINE_ASSETS') === 'true')
				app_template::set_inline_assets(true);

			return basic_template::$method(...$args);
		}

		public static function quick_view(
			string $view_path,
			$page_content='page_content.php'
		){
			if(app_env('APP_INLINE_ASSETS') === 'true')
				app_template::set_inline_assets(true);

			parent::{__FUNCTION__}(
				'samples/'.$view_path,
				$page_content
			);
		}

		public function __construct(...$args)
		{
			parent::{__FUNCTION__}(...$args);

			if(app_env('APP_INLINE_ASSETS') === 'true')
				app_template::set_inline_assets(true);
		}

		public function view(
			$view_path,
			$page_content='page_content.php'
		){
			return parent::{__FUNCTION__}(
				'samples/'.$view_path,
				$page_content
			);
		}
	}
?>