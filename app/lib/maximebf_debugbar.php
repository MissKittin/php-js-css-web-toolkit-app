<?php
	/*
	 * Facade for maximebf DebugBar
	 * Extension for basic_template component
	 *
	 * WARNING!!!
	 *  NEVER EVER ENABLE THIS LIBRARY ON PRODUCTION!!!
	 *
	 * Warning:
	 *  maximebf/debugbar package is recommended
	 *
	 * Note:
	 *  throws an php_debugbar_exception on error
	 *
	 * Starting the bar:
	 *  for the library to work, you need to activate it
	 *  and add routing rules for styles and scripts:
		if(php_debugbar
		::	enable((getenv('APP_ENV') === 'dev'))
		::	set_vendor_dir('./vendor') // must be after enable()
		::	set_vendor_dir('phar://./vendor.phar/vendor') // you can hit and miss, the one above has priority if hit
		::	collectors([ // optional
				'pdo'=>(class_exists('\DebugBar\DataCollector\PDO\PDOCollector'))? new DebugBar\DataCollector\PDO\PDOCollector() : new php_debugbar_dummy()
			])
		::	route(strtok($_SERVER['REQUEST_URI'], '?'))
			exit();
	 *  you have to define collectors at the start, otherwise JavaScript will throw an error
	 *  CSP rules must be added to run the extension
	 *  in the view's template_config.php add at the beginning:
		php_debugbar::get_template_config($view);
	 *  at the end of the page_content.php add:
		<?php php_debugbar::get_page_content(); ?>
	 *
	 * StandardDebugBar instance:
	 *  to get an instance of the DebugBar\StandardDebugBar object, use the get_instance method:
		if(php_debugbar::is_enabled())
			php_debugbar
			::	get_instance()
			->	stackData();
	 *
	 * Access to collectors:
	 *  to call a method of a specific collector, use the get_collector method:
		if(php_debugbar::is_collector_defined('pdo')) // this if is optional
			php_debugbar
			::	get_collector('pdo')
			->	addConnection(
					new PDO('sqlite::memory:')
				);
	 *
	 * Data collection:
	 *  to add data to a collection, use the collector name as the method (php_debugbar::collectorname()):
		php_debugbar::messages()->addMessage('IT WORKS');
		php_debugbar::exceptions()->addException($error);
	 *
	 * Production environment:
	 *  if the extension is disabled, you do not need to remove the code
	 *  all operations will be completed successfully
	 *  and data will go to /dev/null using the php_debugbar_dummy class
	 *
	 * Additional methods:
	 *  get_csp_headers()
	 *   returns an array of CSP settings
	 *  get_html_headers()
	 *   returns a string with HTML headers
	 */

	class php_debugbar_exception extends Exception {}
	class php_debugbar
	{
		protected static $enabled=false;
		protected static $collectors=[];
		protected static $instance=null;
		protected static $vendor_dir=null;

		public static function __callStatic($name, $arguments)
		{
			return static::get_instance()[$name];
		}

		public static function set_vendor_dir(string $vendor_dir)
		{
			if(!static::$enabled)
				return static::class;

			if(
				(static::$vendor_dir === null) &&
				file_exists($vendor_dir)
			)
				static::$vendor_dir=$vendor_dir;

			return static::class;
		}
		public static function enable(bool $condition)
		{
			if(!$condition)
				return static::class;

			if(class_exists('\DebugBar\StandardDebugBar'))
				static::$enabled=true;

			return static::class;
		}
		public static function is_enabled()
		{
			return static::$enabled;
		}

		public static function collectors(array $collectors)
		{
			static::$collectors=$collectors;
			return static::class;
		}
		public static function is_collector_defined(string $collector)
		{
			return isset(static::$collectors[
				$collector
			]);
		}
		public static function get_collector(string $collector)
		{
			if(!isset(static::$collectors[
				$collector
			]))
				throw new php_debugbar_exception(
					'PHP DebugBar '.$collector.' collector is not defined'
				);

			return static::$collectors[
				$collector
			];
		}

		public static function get_instance()
		{
			if(!static::$enabled)
				return new php_debugbar_dummy();

			if(static::$instance === null)
			{
				static::$instance=new DebugBar\StandardDebugBar();

				foreach(static::$collectors as $collector)
					static::$instance->addCollector($collector);

				static
				::	$instance
				->	getJavascriptRenderer()
				->	setBaseUrl('/__PHPDEBUGBAR__');
			}

			return static::$instance;
		}

		public static function route(string $path)
		{
			if(!static::$enabled)
				return false;

			if(static::$vendor_dir === null)
				throw new php_debugbar_exception(
					'vendor directory path is not set - use set_vendor_dir() before enable()'
				);

			if(substr($path, 0, 17) !== '/__PHPDEBUGBAR__/')
				return false;

			if(str_contains($path, '..'))
				return false;

			$asset=''
			.	static::$vendor_dir
			.	'/maximebf/debugbar/src/DebugBar/Resources/'
			.	substr($path, 17);

			if(!is_file($asset))
				return false;

			switch(pathinfo($asset, PATHINFO_EXTENSION))
			{
				case 'css':
					header('Content-Type: text/css');
				break;
				default:
					header('Content-Type: '.mime_content_type($asset));
			}

			readfile($asset);

			return true;
		}

		public static function get_csp_headers()
		{
			return [
				'script-src'=>['\'nonce-phpdebugbar\''],
				'img-src'=>['data:'],
				'font-src'=>[
					'\'self\'',
					'data:'
				],
			];
		}
		public static function get_html_headers()
		{
			return static
			::	get_instance()
			->	getJavascriptRenderer()
			->	setCspNonce('phpdebugbar')
			->	renderHead();
		}

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
			if(!static::$enabled)
				return;

			echo static
			::	get_instance()
			->	getJavascriptRenderer()
			->	render();
		}
	}
	class php_debugbar_dummy implements ArrayAccess
	{
		public function __call($name, $arguments) {}

		public function offsetExists($offset)
		{
			return true;
		}
		public function offsetGet($offset)
		{
			return new self();
		}
		public function offsetSet($offset, $value) {}
		public function offsetUnset($offset) {}
	}
?>