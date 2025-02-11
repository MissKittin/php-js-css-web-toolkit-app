<?php
	class basic_template_exception extends Exception {}

	if(!class_exists('registry'))
	{
		if(file_exists(__DIR__.'/lib/registry.php'))
			require __DIR__.'/lib/registry.php';
		else if(
			defined('TK_LIB') &&
			file_exists(TK_LIB.'/registry.php')
		)
			require TK_LIB.'/registry.php';
		else
			throw new basic_template_exception('registry.php library not found');
	}

	class basic_template extends registry
	{
		protected static $return_content='';
		protected static $assets_path='/assets';
		protected static $assets_filename='basic-template';
		protected static $disable_default_assets=[
			false, // styles
			false // scripts
		];
		protected static $disable_registry_reference=false;
		protected static $favicon=null;
		protected static $inline_assets=[
			false,
			'', // script nonce
			'' // style nonce
		];
		protected static $templating_engine=null;

		protected $do_return_content;

		protected static function load_function($function, $library)
		{
			if(function_exists($function))
				return;

			if(file_exists(__DIR__.'/lib/'.$library))
			{
				require __DIR__.'/lib/'.$library;
				return;
			}

			if(
				defined('TK_LIB') &&
				file_exists(TK_LIB.'/'.$library)
			){
				require TK_LIB.'/'.$library;
				return;
			}

			throw new basic_template_exception($library.' library not found');
		}
		protected static function default_csp_header()
		{
			return [
				'default-src'=>['\'none\''],
				'script-src'=>['\'self\''],
				'connect-src'=>['\'self\''],
				'img-src'=>['\'self\''],
				'style-src'=>['\'self\''],
				'base-uri'=>['\'self\''],
				'form-action'=>['\'self\'']
			];
		}
		protected static function parse_headers_top($view)
		{
			if(isset($view['_csp_header']))
			{
				if(static::$inline_assets[0])
				{
					static::load_function('rand_str_secure', 'rand_str.php');

					static::$inline_assets[1]=rand_str_secure(32);
					static::$inline_assets[2]=rand_str_secure(32);

					$view['_csp_header']['script-src'][]='\'nonce-'.static::$inline_assets[1].'\'';
					$view['_csp_header']['style-src'][]='\'nonce-'.static::$inline_assets[2].'\'';
				}

				?><meta http-equiv="Content-Security-Policy" content="<?php
					foreach($view['_csp_header'] as $csp_param=>$csp_values)
					{
						echo $csp_param;

						foreach($csp_values as $csp_value)
							echo ' '.$csp_value;

						echo ';';
					}
				?>"><?php
			}

			if(isset($view['_html_headers']))
				echo $view['_html_headers'];

			if(isset($view['_meta_robots']))
				{ ?><meta name="robots" content="<?php echo $view['_meta_robots']; ?>"><?php }

			if(isset($view['_opengraph_headers']))
				foreach($view['_opengraph_headers'] as $og_header)
				{
					switch($og_header[0])
					{
						case 'title':
							$view['_opengraph_headers']['_title_defined_']=true;
						break;
						case 'lang':
							$view['_opengraph_headers']['_lang_defined_']=true;
						break;
						case 'description':
							$view['_opengraph_headers']['_description_defined_']=true;
					}

					?><meta property="<?php echo 'og:'.$og_header[0]; ?>" content="<?php echo $og_header[1]; ?>"><?php
				}

			if(
				(isset($view['_title'])) &&
				(!isset($view['_opengraph_headers']['_title_defined_']))
			)
				{ ?><meta property="og:title" content="<?php echo $view['_title']; ?>"><?php }

			if(
				(isset($view['_lang'])) &&
				(!isset($view['_opengraph_headers']['_lang_defined_']))
			)
				{ ?><meta property="og:locale" content="<?php echo $view['_lang']; ?>"><?php }

			if(isset($view['_meta_description']))
			{
				if(isset($view['_opengraph_headers']['_description_defined_']))
					{ ?><meta name="description" content="<?php echo $view['_meta_description']; ?>"><?php }
				else
					{ ?><meta name="description" property="og:description" content="<?php echo $view['_meta_description']; ?>"><?php }
			}

			if(isset($view['_meta_name']))
				foreach($view['_meta_name'] as $meta_name=>$meta_content)
					{ ?><meta name="<?php echo $meta_name; ?>" content="<?php echo $meta_content; ?>"><?php }

			if(isset($view['_meta_property']))
				foreach($view['_meta_property'] as $meta_property=>$meta_content)
					{ ?><meta property="<?php echo $meta_property; ?>" content="<?php echo $meta_content; ?>"><?php }

			if(isset($view['_styles']))
				foreach($view['_styles'] as $style)
				{
					?><link rel="stylesheet" href="<?php echo $style[0]; ?>"<?php
						if(isset($style[1]))
							{ ?> integrity="<?php echo $style[1]; ?>" crossorigin="<?php echo $style[2]; ?>"<?php }
					?>><?php
				}

			if(!static::$disable_default_assets[0])
			{
				if(static::$inline_assets[0])
				{
					?><style nonce="<?php echo static::$inline_assets[2]; ?>"><?php
						foreach(
							array_diff(
								scandir(__DIR__.'/assets/basic-template.css'),
								['.', '..']
							)
							as $inline_style
						)
							readfile(__DIR__.'/assets/basic-template.css/'.$inline_style);
					?></style><?php
				}
				else
					{ ?><link rel="stylesheet" href="<?php echo static::$assets_path; ?>/<?php echo static::$assets_filename; ?>.css"><?php }
			}

			if(static::$favicon !== null)
				readfile(static::$favicon);

			if(isset($view['_scripts_top']))
				foreach($view['_scripts_top'] as $script)
				{
					?><script <?php
						if(isset($script[3]))
							echo 'type="'.$script[3].'" ';
					?>src="<?php echo $script[0]; ?>"<?php
						if(isset($script[1]))
							{ ?> integrity="<?php echo $script[1]; ?>" crossorigin="<?php echo $script[2]; ?>"<?php }

						if(isset($script[4]))
							echo ' '.$script[4];
					?>></script><?php
				}
		}
		protected static function parse_headers_bottom($view)
		{
			if(!static::$disable_default_assets[1])
			{
				if(static::$inline_assets[0])
				{
					?><script nonce="<?php echo static::$inline_assets[1]; ?>"><?php
						require __DIR__.'/assets/basic-template.js/main.php';
					?></script><?php
				}
				else
					{ ?><script src="<?php echo static::$assets_path; ?>/<?php echo static::$assets_filename; ?>.js"></script><?php }
			}

			if(isset($view['_scripts']))
				foreach($view['_scripts'] as $script)
				{
					?><script <?php
						if(isset($script[3]))
							echo 'type="'.$script[3].'" ';
					?>src="<?php echo $script[0]; ?>"<?php
						if($script[1] !== null)
							{ ?> integrity="<?php echo $script[1]; ?>" crossorigin="<?php echo $script[2]; ?>"<?php }
					?>></script><?php
				}
		}

		public static function disable_default_styles(bool $value=true)
		{
			static::$disable_default_assets[0]=$value;
			return static::class;
		}
		public static function disable_default_scripts(bool $value=true)
		{
			static::$disable_default_assets[1]=$value;
			return static::class;
		}
		public static function set_assets_path(string $path)
		{
			static::$assets_path=$path;
			return static::class;
		}
		public static function set_assets_filename(string $name)
		{
			static::$assets_filename=$name;
			return static::class;
		}
		public static function set_inline_assets(bool $inline_assets)
		{
			static::$inline_assets[0]=$inline_assets;
			return static::class;
		}
		public static function set_favicon(string $path)
		{
			if(!file_exists($path))
				throw new basic_template_exception($path.' does not exist');

			static::$favicon=realpath($path);

			return static::class;
		}
		public static function set_templating_engine(callable $callback)
		{
			static::$templating_engine[0]=$callback;
			return static::class;
		}
		public static function quick_view(string $view_path, string $page_content='page_content.php')
		{
			$view['_csp_header']=static::default_csp_header();

			if(file_exists($view_path.'/template_config.php'))
				include $view_path.'/template_config.php';

			require __DIR__.'/view.php';
		}

		public function __construct(bool $return_content=false)
		{
			$this->registry['_csp_header']=static::default_csp_header();
			$this->do_return_content=$return_content;
		}

		public function disable_registry_reference(bool $value=true)
		{
			$this->disable_registry_reference=$value;
			return $this;
		}
		public function set_variable(string $variable, $value)
		{
			$this->registry[$variable]=$value;
			return $this;
		}
		public function add_csp_header(string $section, string $value)
		{
			$this->registry['_csp_header'][$section][]=$value;
			return $this;
		}
		public function add_html_header(string $header)
		{
			if(!isset($this->registry['_html_headers']))
				$this->registry['_html_headers']='';

			$this->registry['_html_headers'].=$header;

			return $this;
		}
		public function add_link_header(array $params)
		{
			$this->add_html_header('<link');

			foreach($params as $param=>$value)
				$this->add_html_header(' '
				.	$param
				.	'='
				.	'"'.$value.'"'
				);

			$this->add_html_header('>');

			return $this;
		}
		public function add_meta_name_header(string $name, string $content)
		{
			$this->registry['_meta_name'][$name]=$content;
			return $this;
		}
		public function add_meta_property_header(string $property, string $content)
		{
			$this->registry['_meta_property'][$property]=$content;
			return $this;
		}
		public function add_og_header(string $property, string $content)
		{
			$this->registry['_opengraph_headers'][]=[$property, $content];
			return $this;
		}
		public function add_style_header(
			string $path,
			?string $integrity=null,
			string $crossorigin='anonymous'
		){
			$this->registry['_styles'][]=[
				$path,
				$integrity,
				$crossorigin
			];

			return $this;
		}
		public function add_script_header(
			string $path,
			?string $integrity=null,
			string $crossorigin='anonymous',
			?string $type=null
		){
			$this->registry['_scripts'][]=[
				$path,
				$integrity,
				$crossorigin,
				$type
			];

			return $this;
		}
		public function add_script_header_top(
			string $path,
			?string $integrity=null,
			string $crossorigin='anonymous',
			?string $type=null,
			?string $options=null
		){
			$this->registry['_scripts_top'][]=[
				$path,
				$integrity,
				$crossorigin,
				$type,
				$options
			];

			return $this;
		}
		public function add_inline_style(
			string $content,
			bool $add_csp_hash=true,
			bool $add_csp_nonce=false
		){
			$this->add_html_header('<style');

			if($add_csp_hash)
			{
				static::load_function('generate_csp_hash', 'generate_csp_hash.php');
				$this->add_csp_header('style-src', generate_csp_hash($content));
			}
			else if($add_csp_nonce)
			{
				static::load_function('rand_str_secure', 'rand_str.php');

				$nonce_value=rand_str_secure(32);

				$this
				->	add_csp_header('style-src', '\'nonce-'.$nonce_value.'\'')
				->	add_html_header(' nonce="'.$nonce_value.'"');
			}

			$this->add_html_header('>'.$content.'</style>');

			return $this;
		}
		public function add_inline_script(
			string $content,
			bool $add_csp_hash=true,
			bool $add_csp_nonce=false
		){
			$this->add_html_header('<script');

			if($add_csp_hash)
			{
				static::load_function('generate_csp_hash', 'generate_csp_hash.php');
				$this->add_csp_header('script-src', generate_csp_hash($content));
			}
			else if($add_csp_nonce)
			{
				static::load_function('rand_str_secure', 'rand_str.php');

				$nonce_value=rand_str_secure(32);

				$this
				->	add_csp_header('script-src', '\'nonce-'.$nonce_value.'\'')
				->	add_html_header(' nonce="'.$nonce_value.'"');
			}

			$this->add_html_header('>'.$content.'</script>');

			return $this;
		}
		public function view(string $view_path, string $page_content='page_content.php')
		{
			if($this->disable_registry_reference)
				$view=$this->registry;
			else
				$view=&$this->registry;

			if($this->do_return_content)
				ob_start(function($content){
					static::$return_content.=$content;
					return $content;
				});

			if(file_exists($view_path.'/template_config.php'))
				include $view_path.'/template_config.php';

			require __DIR__.'/view.php';

			if($this->do_return_content)
			{
				ob_end_clean();

				$return_content=static::$return_content;
				static::$return_content='';

				return $return_content;
			}
		}
	}
?>