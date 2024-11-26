<?php
	/*
	 * Default http headers
	 * for routes using views
	 */

	header('X-Frame-Options: SAMEORIGIN');
	header('X-XSS-Protection: 0');
	header('X-Content-Type-Options: nosniff');

	class app_template implements ArrayAccess
	{
		/*
		 * An overlay for the basic_template component
		 * that saves typing
		 * also loads the component on demand,
		 * sets the path to assets if the application's public directory is not in the document root
		 * and calls fastcgi_finish_request() if available
		 *
		 * You can also get url to public directory:
			app_template::get_public_dir_url(); // returns empty string if root or '/subdir'
		 * and finish the request:
			app_template::finish_request();
		 *
		 * Warning:
		 *  basic_template component is required
		 *
		 * Required $_SERVER variables:
		 *  $_SERVER['SCRIPT_NAME']
		 */

		protected static $public_dir=null;

		protected $instance=null;
		protected $return_content;

		protected static function _auto_set_assets_path()
		{
			static::get_public_dir_url();

			if(static::$public_dir !== '')
				basic_template::set_assets_path(
					static::$public_dir.'/assets'
				);
		}

		public static function __callStatic($method, $args)
		{
			if(!class_exists('basic_template'))
				require APP_COM.'/basic_template/main.php';

			return basic_template::$method(...$args);
		}

		public static function finish_request()
		{
			if(function_exists('fastcgi_finish_request'))
				fastcgi_finish_request();
		}
		public static function get_public_dir_url()
		{
			if(static::$public_dir !== null)
				return static::$public_dir;

			$script_dir=dirname($_SERVER['SCRIPT_NAME']);

			if(
				($script_dir === '/') ||
				($script_dir === '\\')
			){
				static::$public_dir='';
				return '';
			}

			static::$public_dir=$script_dir;

			return $script_dir;
		}
		public static function quick_view(
			string $view_path,
			$page_content='page_content.php'
		){
			if(!class_exists('basic_template'))
				require APP_COM.'/basic_template/main.php';

			static::_auto_set_assets_path();

			basic_template::{__FUNCTION__}(
				APP_VIEW.'/'.$view_path,
				$page_content
			);

			static::finish_request();
		}

		public function __construct(bool $return_content=false)
		{
			if(!class_exists('basic_template'))
				require APP_COM.'/basic_template/main.php';

			static::_auto_set_assets_path();

			$this->return_content=$return_content;
			$this->instance=new basic_template($return_content);
		}
		public function __call($method, $args)
		{
			return $this
			->	instance
			->	$method(...$args);
		}
		public function __get($key)
		{
			return $this
			->	instance
			->	__get($key);
		}
		public function __set($key, $value)
		{
			$this
			->	instance
			->	__set($key, $value);
		}

		public function offsetSet($key, $value)
		{
			return $this
			->	instance
			->	__set($key, $value);
		}
		public function offsetExists($key)
		{
			return $this
			->	instance
			->	offsetExists($key);
		}
		public function offsetGet($key)
		{
			return $this
			->	instance
			->	__get($key);
		}
		public function offsetUnset($key)
		{
			return $this
			->	instance
			->	offsetUnset($key);
		}

		public function view($view_path, $page_content='page_content.php')
		{
			$output=$this->instance->{__FUNCTION__}(
				APP_VIEW.'/'.$view_path,
				$page_content
			);

			if(!$this->do_return_content)
				static::finish_request();

			return $output;
		}
	}
?>