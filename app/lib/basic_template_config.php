<?php
	/*
	 * Configuration interface
	 * Extension for the basic_template component
	 *
	 * template configuration is done via registry
	 * this is convenient for automation, but not very readable for the developer
	 * this library provides a clear template configuration interface
	 * in addition, all methods return self - you can write the configuration
	 *  from start to end as one chain of static methods
	 *
	 * Note:
	 *  if you want to set a special variable (starting with "_") e.g. $view['_title']='my title'
	 *  skip the first character e.g. basic_template_config::title('my title')
	 *
	 * Special methods and their equivalents:
	 *  csp
			basic_template_config::csp('script-src', '\'sha256-something\'')
			$view['_csp_header']['script-src'][]='\'sha256-something\'';
	 *  disable_default_styles
			basic_template_config::disable_default_styles()
			basic_template::disable_default_styles()
	 *  disable_default_scripts
			basic_template_config::disable_default_scripts()
			basic_template::disable_default_scripts()
	 *  favicon
			basic_template_config::favicon(__DIR__.'/favicon.html')
			basic_template::set_favicon(__DIR__.'/favicon.html')
	 *  html_headers
			basic_template_config::html_headers('<my-header tag>')
			$view['_html_headers'].='<my-header tag>'
	 *  meta_name
			basic_template_config::meta_name('name', 'value')
			$view['_meta_name']['name']='value'
	 *  meta_property
			basic_template_config::meta_property('property', 'value')
			$view['_meta_property']['property']='value'
	 *  og
			basic_template_config::og('type', 'website')
			$view['_opengraph_headers'][]=['type', 'website']
	 *  styles
			basic_template_config::styles('/assets/style.css')
			$view['_styles'][]=['/assets/style.css', null, null]

			basic_template_config::styles('https://another.server/style.css', 'sha384-hash', 'anonymous')
			$view['_styles'][]=['https://another.server/style.css', 'sha384-hash', 'anonymous']
	 *  scripts
			basic_template_config::scripts('/assets/script.js')
			$view['_scripts'][]=['/assets/script.js', null, null]

			basic_template_config::scripts('https://another.server/script.js', 'sha384-hash', 'anonymous')
			$view['_scripts'][]=['https://another.server/script.js', 'sha384-hash', 'anonymous']

			basic_template_config::scripts('https://another.server/script.js', 'sha384-hash', 'anonymous', 'module')
			$view['_scripts'][]=['https://another.server/script.js', 'sha384-hash', 'anonymous', 'module']
	 *  modules
			basic_template_config::modules('/assets/module.js')
			$view['_scripts'][]=['/assets/module.js', null, null, 'module']

			basic_template_config::modules('https://another.server/script.js', 'sha384-hash', 'anonymous')
			$view['_scripts'][]=['https://another.server/script.js', 'sha384-hash', 'anonymous', 'module']
	 *  scripts_top
			basic_template_config::scripts_top('/assets/script.js')
			$view['_scripts_top'][]=['/assets/script.js', null, null, null, null]

			basic_template_config::scripts_top('https://another.server/script.js', 'sha384-hash', 'anonymous')
			$view['_scripts_top'][]=['https://another.server/script.js', 'sha384-hash', 'anonymous', null, null]

			basic_template_config::scripts_top('https://another.server/script.js', 'sha384-hash', 'anonymous', 'defer')
			$view['_scripts_top'][]=['https://another.server/script.js', 'sha384-hash', 'anonymous', null, 'defer']

			basic_template_config::scripts_top('https://another.server/script.js', 'sha384-hash', 'anonymous', 'defer', 'module')
			$view['_scripts_top'][]=['https://another.server/script.js', 'sha384-hash', 'anonymous', 'module', 'defer']
	 *  scripts_top_op
			basic_template_config::scripts_top_op('/assets/script.js', 'defer')
			$view['_scripts_top'][]=['/assets/script.js', null, null, null, 'defer']

			basic_template_config::scripts_top_op('https://another.server/script.js', 'defer', 'sha384-hash', 'anonymous')
			$view['_scripts_top'][]=['https://another.server/script.js', 'sha384-hash', 'anonymous', 'null', 'defer']

			basic_template_config::scripts_top_op('https://another.server/script.js', 'defer', 'sha384-hash', 'anonymous', 'module')
			$view['_scripts_top'][]=['https://another.server/script.js', 'sha384-hash', 'anonymous', 'module', 'defer']
	 *  modules_top
			basic_template_config::modules_top('/assets/module.js')
			$view['_scripts_top'][]=['/assets/module.js', null, null, 'module', null]

			basic_template_config::modules_top('https://another.server/script.js', 'sha384-hash', 'anonymous')
			$view['_scripts_top'][]=['https://another.server/script.js', 'sha384-hash', 'anonymous', 'module', null]

			basic_template_config::modules_top('https://another.server/script.js', 'sha384-hash', 'anonymous', 'defer')
			$view['_scripts_top'][]=['https://another.server/script.js', 'sha384-hash', 'anonymous', 'module', 'defer']
	 *
	 * Usage (in template_config.php):
		basic_template_config($view, static::class) // initialize class
		::	i_dont_want_this_variable('i_dont_want_this_value')
		::	my_variable('my value') // $view['my_variable']='my value'
		::	i_dont_want_this_variable(); // unset($view['i_dont_want_this_variable'])

		// some code

		basic_template_config // use previously initialized class
		::	my_another_variable('my-another-value');
	 */

	class basic_template_config
	{
		protected static $view;
		protected static $basic_template_class;

		public static function __callStatic($name, $arguments)
		{
			if(!isset($arguments[0]))
			{
				unset(static::$view[$name]);
				return static::class;
			}

			switch($name)
			{
				case 'lang':
				case 'head_prefix':
				case 'title':
				case 'meta_robots':
				case 'meta_description':
					$name='_'.$name;
			}

			static::$view[$name]=$arguments[0];

			return static::class;
		}

		public static function _set_view(
			&$view,
			$basic_template_class
		){
			static::$view=&$view;
			static::$basic_template_class=$basic_template_class;

			return static::class;
		}

		public static function csp(string $section, string $parameter)
		{
			static::$view['_csp_header'][$section][]=$parameter;
			return static::class;
		}
		public static function disable_default_styles()
		{
			static
			::	$basic_template_class
			::	disable_default_styles();

			return static::class;
		}
		public static function disable_default_scripts()
		{
			static
			::	$basic_template_class
			::	disable_default_scripts();

			return static::class;
		}
		public static function favicon(string $path)
		{
			static
			::	$basic_template_class
			::	set_favicon($path);

			return static::class;
		}
		public static function html_headers(string $content)
		{
			if(!isset(static::$view['_html_headers']))
				static::$view['_html_headers']='';

			static::$view['_html_headers'].=$content;

			return static::class;
		}
		public static function meta_name(
			string $name,
			string $content
		){
			static::$view['_meta_name'][$name]=$content;
			return static::class;
		}
		public static function meta_property(
			string $name,
			string $content
		){
			static::$view['_meta_property'][$name]=$content;
			return static::class;
		}
		public static function og(...$parameters)
		{
			static::$view['_opengraph_headers'][]=$parameters;
			return static::class;
		}
		public static function styles(
			string $path,
			?string $integrity=null,
			?string $crossorigin=null
		){
			static::$view['_styles'][]=[
				$path,
				$integrity,
				$crossorigin
			];

			return static::class;
		}
		public static function scripts(
			string $path,
			?string $integrity=null,
			?string $crossorigin=null,
			?string $type=null
		){
			static::$view['_scripts'][]=[
				$path,
				$integrity,
				$crossorigin,
				$type
			];

			return static::class;
		}
		public static function modules(
			string $path,
			?string $integrity=null,
			?string $crossorigin=null
		){
			return static::scripts(
				$path,
				$integrity,
				$crossorigin,
				'module'
			);
		}
		public static function scripts_top(
			string $path,
			?string $integrity=null,
			?string $crossorigin=null,
			?string $options=null,
			?string $type=null
		){
			static::$view['_scripts_top'][]=[
				$path,
				$integrity,
				$crossorigin,
				$type,
				$options
			];

			return static::class;
		}
		public static function scripts_top_op(
			string $path,
			string $options,
			?string $integrity=null,
			?string $crossorigin=null,
			?string $type=null
		){
			return static::scripts_top(
				$path,
				$integrity,
				$crossorigin,
				$options,
				$type
			);
		}
		public static function modules_top(
			string $path,
			?string $integrity=null,
			?string $crossorigin=null,
			?string $options=null
		){
			return static::scripts_top(
				$path,
				$integrity,
				$crossorigin,
				$options,
				'module'
			);
		}
	}

	function basic_template_config(
		&$view,
		$basic_template_class
	){
		return basic_template_config
		::	_set_view(
				$view,
				$basic_template_class
			);
	}
?>