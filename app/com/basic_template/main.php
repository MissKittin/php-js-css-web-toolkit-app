<?php
	if(!class_exists('registry'))
		require TK_LIB.'/registry.php';

	class basic_template extends registry
	{
		protected static $return_content='';

		protected $do_return_content;

		public static function quick_view(string $view_path, string $page_content='page_content.php')
		{
			$view['csp_header']=static::default_csp_header();

			if(file_exists($view_path.'/template_config.php'))
				include $view_path.'/template_config.php';

			require __DIR__.'/view.php';
		}

		private static function default_csp_header()
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
		private static function parse_headers($view)
		{
			if(isset($view['csp_header']))
			{
				?><meta http-equiv="Content-Security-Policy" content="<?php
				foreach($view['csp_header'] as $csp_param=>$csp_values)
				{
					echo $csp_param;
					foreach($csp_values as $csp_value)
						echo ' '.$csp_value;
					echo ';';
				}
				?>"><?php
			}

			if(isset($view['meta_robots']))
				{ ?><meta name="robots" content="<?php echo $view['meta_robots']; ?>"><?php }
			if(isset($view['title']))
				{ ?><meta property="og:title" content="<?php echo $view['title']; ?>"><?php }
			if(isset($view['lang']))
				{ ?><meta property="og:locale" content="<?php echo $view['lang']; ?>"><?php }
			if(isset($view['meta_description']))
				{ ?><meta name="description" property="og:description" content="<?php echo $view['meta_description']; ?>"><?php }

			if(isset($view['meta_name']))
				foreach($view['meta_name'] as $meta_name=>$meta_content)
					{ ?><meta name="<?php echo $meta_name; ?>" content="<?php echo $meta_content; ?>"><?php }
			if(isset($view['meta_property']))
				foreach($view['meta_property'] as $meta_property=>$meta_content)
					{ ?><meta property="<?php echo $meta_property; ?>" content="<?php echo $meta_content; ?>"><?php }

			if(isset($view['html_headers']))
				echo $view['html_headers'];

			if(isset($view['styles']))
				foreach($view['styles'] as $style)
					{ ?><link rel="stylesheet" href="<?php echo $style; ?>"><?php }
		}

		public function __construct(bool $return_content=false)
		{
			$this->registry['csp_header']=static::default_csp_header();
			$this->do_return_content=$return_content;
		}

		public function set_variable(string $variable, $value)
		{
			$this->registry[$variable]=$value;
			return $this;
		}
		public function add_csp_header(string $section, string $value)
		{
			$this->registry['csp_header'][$section][]=$value;
			return $this;
		}
		public function add_html_header(string $header)
		{
			if(!isset($this->registry['html_headers']))
				$this->registry['html_headers']='';

			$this->registry['html_headers'].=$header;

			return $this;
		}
		public function add_meta_name_header(string $name, string $content)
		{
			$this->registry['meta_name'][$name]=$content;
			return $this;
		}
		public function add_meta_property_header(string $property, string $content)
		{
			$this->registry['meta_property'][$property]=$content;
			return $this;
		}
		public function add_style_header(string $path)
		{
			$this->registry['styles'][]=$path;
			return $this;
		}
		public function add_script_header(string $path)
		{
			$this->registry['scripts'][]=$path;
			return $this;
		}
		public function view(string $view_path, string $page_content='page_content.php')
		{
			$view=$this->registry;

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