<?php
	/*
	 * Example template
	 *
	 * Warning:
	 *  registry.php library is required
	 *
	 * Note:
	 *  you can use $template->variable='value' and $template['variable']='value'
	 *   and setters
	 *  pass true to the constructor to have the view method return the content instead of echoing it
	 *
	 * Methods:
	 *  set_variable(string_variable, value)
	 *   add value to registry
	 *  add_csp_header(string_section, string_value)
	 *   where string_section is eg 'script-src' and string_value is '\'unsafe-hashes\''
	 *  add_html_header(string_header)
	 *   eg '<my-header-tag content="my-content">'
	 *  add_meta_name_header(string_name, string_content)
	 *   <meta name="string_name" content="string_content">
	 *  add_meta_property_header(string_property, string_content)
	 *   <meta property="string_property" content="string_content">
	 *  add_style_header(string_path)
	 *   link rel="stylesheet"
	 *  add_script_header(string_path)
	 *   script src after page content
	 *  view(string_view_path, string_page_content='page_content.php')
	 *   load configuration files from string_view_path and run template with contents from string_view_path/string_page_content
	 *   note: if string_page_content ends with '.php', require will be used instead of readfile
	 *  [static] quick_view(string_view_path, string_page_content='page_content.php')
	 *   same as the view(), use when you don't need to set any additional variables
	 *   note: if string_page_content ends with '.php', require will be used instead of readfile
	 *
	 * Variables:
	 *  lang [string]
	 *   <html lang=""> and <meta property="og:locale">
	 *  title [string]
	 *   <title> and <meta property="og:title">
	 *  csp_header [array_assoc]
	 *   use add_csp_header()
	 *  meta_robots [string]
	 *   (no)index,(no)follow
	 *  meta_description [string]
	 *   <meta name="description" property="og:description">
	 *  meta_name [array_assoc]
	 *   use add_meta_name_header()
	 *  meta_property [array_assoc]
	 *   use add_meta_property_header()
	 *  html_headers [string]
	 *   use add_html_header()
	 *  styles [array]
	 *   use add_style_header()
	 *  script [array]
	 *   use add_style_header()
	 *
	 * Favicon:
	 *  paste the headers into the favicon.html file
	 *  in the views directory.
	 *  the content will be appended to the head section
	 */

	if(!class_exists('registry'))
		require TK_LIB.'/registry.php';

	class default_template extends registry
	{
		protected static $do_return_content;
		protected static $return_content='';

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
			static::$do_return_content=$return_content;
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

			if(static::$do_return_content)
				ob_start(function($content){
					static::$return_content.=$content;
					return $content;
				});

			if(file_exists($view_path.'/template_config.php'))
				include $view_path.'/template_config.php';

			require __DIR__.'/view.php';

			if(static::$do_return_content)
			{
				ob_end_clean();

				$return_content=static::$return_content;
				static::$return_content='';

				return $return_content;
			}
		}
	}
?>