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
		 * An overlay for the default template
		 * that saves typing
		 * also loads basic_template component on demand
		 * and calls fastcgi_finish_request() if available
		 */

		protected $instance=null;
		protected $return_content;

		public static function __callStatic($method, $args)
		{
			if(!class_exists('basic_template'))
				require APP_COM.'/basic_template/main.php';

			return basic_template::$method(...$args);
		}

		public static function quick_view($view_path, $page_content='page_content.php')
		{
			if(!class_exists('basic_template'))
				require APP_COM.'/basic_template/main.php';

			basic_template::{__FUNCTION__}(
				APP_VIEW.'/samples/'.$view_path,
				$page_content
			);

			if(function_exists('fastcgi_finish_request'))
				fastcgi_finish_request();
		}

		public function __construct(bool $return_content=false)
		{
			if(!class_exists('basic_template'))
				require APP_COM.'/basic_template/main.php';

			$this->return_content=$return_content;
			$this->instance=new basic_template($return_content);
		}
		public function __call($method, $args)
		{
			return $this->instance->$method(...$args);
		}
		public function __get($key)
		{
			return $this->instance->__get($key);
		}
		public function __set($key, $value)
		{
			$this->instance->__set($key, $value);
		}

		public function offsetSet($key, $value)
		{
			return $this->instance->__set($key, $value);
		}
		public function offsetExists($key)
		{
			return $this->instance->offsetExists($key);
		}
		public function offsetGet($key)
		{
			return $this->instance->__get($key);
		}
		public function offsetUnset($key)
		{
			return $this->instance->offsetUnset($key);
		}

		public function view($view_path, $page_content='page_content.php')
		{
			$this->instance->{__FUNCTION__}(
				APP_VIEW.'/samples/'.$view_path,
				$page_content
			);

			if((!$this->do_return_content) && function_exists('fastcgi_finish_request'))
				fastcgi_finish_request();
		}
	}
?>