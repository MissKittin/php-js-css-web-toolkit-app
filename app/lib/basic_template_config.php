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
	 *  link
			basic_template_config::link('REL', [
				'opt1'=>'val1',
				'opt2'=>'null'
			])
			$view['_html_headers'].='<link rel="REL" opt1="val1" opt2>'
	 *  link_href
			basic_template_config::link_rel('stylesheet', 'https://my.server/style.css')
			$view['_html_headers'].='<link rel="stylesheet" href="https://my.server/style.css">'
	 *  link_alternate
			basic_template_config::link_alternate('en', 'https://en.my.server/page')
			$view['_html_headers'].='<link rel="alternate" hreflang="en" href="https://en.my.server/page">'
	 *  link_author
			basic_template_config::link_author('https://my.server/author')
			$view['_html_headers'].='<link rel="author" href="https://my.server/author">'
	 *  link_canonical
			basic_template_config::link_canonical('https://my.server/page')
			$view['_html_headers'].='<link rel="canonical" href="https://my.server/page">'
	 *  link_dns_prefetch
			basic_template_config::link_dns_prefetch('https://another.server/')
			$view['_html_headers'].='<link rel="dns-prefetch" href="https://another.server/">'

			basic_template_config::link_dns_prefetch('https://another.server/', null)
			$view['_html_headers'].='<link rel="dns-prefetch" href="https://another.server/" crossorigin>'

			basic_template_config::link_dns_prefetch('https://another.server/', 'anonymous')
			$view['_html_headers'].='<link rel="dns-prefetch" href="https://another.server/" crossorigin="anonymous">'
	 *  link_help
			basic_template_config::link_help('https://my.server/help')
			$view['_html_headers'].='<link rel="help" href="https://my.server/help">'
	 *  link_license
			basic_template_config::link_license('https://my.server/license')
			$view['_html_headers'].='<link rel="license" href="https://my.server/license">'
	 *  link_manifest
			basic_template_config::link_manifest('https://my.server/manifest.json')
			$view['_html_headers'].='<link rel="manifest" href="https://my.server/manifest.json">'
	 *  link_modulepreload
			basic_template_config::link_modulepreload('https://my.server/module.js')
			$view['_html_headers'].='<link rel="modulepreload" href="https://my.server/module.js">'
	 *  link_next
			basic_template_config::link_next('https://my.server/page2')
			$view['_html_headers'].='<link rel="next" href="https://my.server/page2">'
	 *  link_pingback
			basic_template_config::link_pingback('https://my.server/pingback')
			$view['_html_headers'].='<link rel="pingback" href="https://my.server/pingback">'
	 *  link_preconnect
			basic_template_config::link_preconnect('https://another.server/')
			$view['_html_headers'].='<link rel="preconnect" href="https://another.server/">'

			basic_template_config::link_preconnect('https://another.server/', null)
			$view['_html_headers'].='<link rel="preconnect" href="https://another.server/" crossorigin>'

			basic_template_config::link_preconnect('https://another.server/', 'anonymous')
			$view['_html_headers'].='<link rel="preconnect" href="https://another.server/" crossorigin="anonymous">'
	 *  link_prefetch
			basic_template_config::link_prefetch('https://my.server/resource.js')
			$view['_html_headers'].='<link rel="prefetch" href="https://my.server/resource.js">'
	 *  link_preload
			basic_template_config::link_preload('https://my.server/resource.webp')
			$view['_html_headers'].='<link rel="preload" href="https://my.server/resource.webp">'

			basic_template_config::link_preload('https://my.server/resource.css', 'style')
			$view['_html_headers'].='<link rel="preload" href="https://my.server/resource.css" as="style">'

			basic_template_config::link_preload('https://my.server/resource.webp', null, 'image/webp')
			$view['_html_headers'].='<link rel="preload" href="https://my.server/resource.webp" type="image/webp">'
	 *  link_prev
			basic_template_config::link_prev('https://my.server/page1')
			$view['_html_headers'].='<link rel="prev" href="https://my.server/page1">'
	 *  link_privacy_policy
			basic_template_config::link_privacy_policy('https://my.server/privacy-policy')
			$view['_html_headers'].='<link rel="privacy-policy" href="https://my.server/privacy-policy">'
	 *  link_search
			basic_template_config::link_search('https://my.server/search')
			$view['_html_headers'].='<link rel="search" href="https://my.server/search">'
	 *  link_tos
			basic_template_config::link_tos('https://my.server/terms-of-service')
			$view['_html_headers'].='<link rel="terms-of-service" href="https://my.server/terms-of-service">'
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

		::	cache('name', 'cached value') // add to cache

		::	i_dont_want_this_variable('i_dont_want_this_value') // $view['i_dont_want_this_variable']='i_dont_want_this_value'
		::	my_variable('my value') // $view['my_variable']='my value'
		::	i_dont_want_this_variable() // unset($view['i_dont_want_this_variable'])

		::	my_second_variable(basic_template_cache('name', 'defaultvalue')) // fetch from cache and assign (the second argument is optional) ($view['my_second_variable']='cached value')
		::	my_third_variable(basic_template_config::cache_get('name', 'defaultvalue')); // alternative (the second argument is optional) ($view['my_third_variable']='cached value')
		::	my_fourth_variable(basic_template_cache('not-cached', 'this is defaultvalue')); // this is not in cache - assign default value ($view['my_fourth_variable']='this is defaultvalue')
		::	my_fifth_variable(basic_template_cache('not-cached')); // this is not in cache - assign null ($view['my_fourth_variable']=null)

		// some code

		basic_template_config // use previously initialized class
		::	my_another_variable('my-another-value');
	 */

	class basic_template_config
	{
		protected static $view;
		protected static $basic_template_class;
		protected static $cache=[];

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

		public static function cache(string $key, $value)
		{
			static::$cache[$key]=$value;
			return static::class;
		}
		public static function cache_get(
			string $key,
			$default_value=null
		){
			if(isset(static::$cache[$key]))
				return static::$cache[$key];

			return $default_value;
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
		public static function link(
			string $rel,
			array $params
		){
			if(!isset(static::$view['_html_headers']))
				static::$view['_html_headers']='';

			static::$view['_html_headers'].='<link rel="'.$rel.'"';

			foreach($params as $param=>$value)
			{
				if($value === null)
				{
					static::$view['_html_headers'].=' '.$param;
					continue;
				}

				static::$view['_html_headers'].=' '
				.	$param
				.	'='
				.	'"'.$value.'"';
			}

			static::$view['_html_headers'].='>';

			return static::class;
		}
		public static function link_href(
			string $rel,
			string $href
		){
			return static::link($rel, [
				'href'=>$href
			]);
		}
		public static function link_alternate(
			string $hreflang,
			string $href
		){
			return static::link('alternate', [
				'hreflang'=>$hreflang,
				'href'=>$href
			]);
		}
		public static function link_author(string $href)
		{
			return static::link_href('author', $href);
		}
		public static function link_canonical(string $href)
		{
			return static::link_href('canonical', $href);
		}
		public static function link_dns_prefetch(
			string $href,
			$crossorigin=false
		){
			if($crossorigin === false)
				return static::link('dns-prefetch', [
					'href'=>$href
				]);

			return static::link('dns-prefetch', [
				'href'=>$href,
				'crossorigin'=>$crossorigin
			]);
		}
		public static function link_help(string $href)
		{
			return static::link_href('help', $href);
		}
		public static function link_license(string $href)
		{
			return static::link_href('license', $href);
		}
		public static function link_manifest(string $href)
		{
			return static::link_href('manifest', $href);
		}
		public static function link_modulepreload(string $href)
		{
			return static::link_href('modulepreload', $href);
		}
		public static function link_next(string $href)
		{
			return static::link_href('next', $href);
		}
		public static function link_pingback(string $href)
		{
			return static::link_href('pingback', $href);
		}
		public static function link_preconnect(
			string $href,
			$crossorigin=false
		){
			if($crossorigin === false)
				return static::link('preconnect', [
					'href'=>$href
				]);

			return static::link('preconnect', [
				'href'=>$href,
				'crossorigin'=>$crossorigin
			]);
		}
		public static function link_prefetch(string $href)
		{
			return static::link_href('prefetch', $href);
		}
		public static function link_preload(
			string $href,
			?string $as=null,
			?string $type=null
		){
			$params=['href'=>$href];

			if($as !== null)
				$params['as']=$as;

			if($type !== null)
				$params['type']=$type;

			return static::link('preload', $params);
		}
		public static function link_prev(string $href)
		{
			return static::link_href('prev', $href);
		}
		public static function link_privacy_policy(string $href)
		{
			return static::link_href('privacy-policy', $href);
		}
		public static function link_search(string $href)
		{
			return static::link_href('search', $href);
		}
		public static function link_tos(string $href)
		{
			return static::link_href('terms-of-service', $href);
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
		return basic_template_config::_set_view(
			$view,
			$basic_template_class
		);
	}
	function basic_template_cache(
		string $key,
		$default_value=null
	){
		return basic_template_config::cache_get(
			$key,
			$default_value
		);
	}
?>