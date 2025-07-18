<?php
	/*
	 * Modular output buffer
	 *
	 * Modules:
	 *  ob_adapter_obminifier - adapter for ob_minifier.php library
	 *  ob_adapter_obsfucator - adapter for ob_sfucator.php library
	 *  ob_adapter_gzip - ob_gzhandler replacement
	 *   zlib extension is required
	 *   does not require additional libraries
	 *   throws an ob_adapter_exception on error
	 *  ob_adapter_filecache - basic file cache
	 *   does not require additional libraries
	 *  ob_adapter_gunzip - decompress if browser does not support gzip
	 *   zlib extension is required
	 *   does not require additional libraries
	 *   throws an ob_adapter_exception on error
	 *
	 * Usage:
		if(ob_adapter
		::	add(new ob_adapter_module1())
		::	add(new ob_adapter_module2())
		::	start())
			exit(); // or return;
	 */

	class ob_adapter_exception extends Exception {}

	interface ob_adapter_module
	{
		public function exec($buffer);
	}

	abstract class ob_adapter
	{
		protected static $instances=[];
		protected static $exit=false;

		protected static function exec($buffer, $phase)
		{
			foreach(static::$instances as $instance)
				$buffer=$instance->exec($buffer, $phase);

			return $buffer;
		}

		public static function add(ob_adapter_module $instance)
		{
			if(static::$exit)
				return static::class;

			static::$instances[]=$instance;

			return static::class;
		}
		public static function start()
		{
			if(static::$exit)
				return true;

			ob_start(__CLASS__.'::exec');

			return false;
		}
		public static function toggle_exit()
		{
			static::$exit=true;
		}
	}

	class ob_adapter_obminifier implements ob_adapter_module
	{
		public function __construct()
		{
			if(!function_exists('ob_minifier'))
				require TK_LIB.'/ob_minifier.php';
		}

		public function exec($buffer)
		{
			return ob_minifier($buffer);
		}
	}
	class ob_adapter_obsfucator implements ob_adapter_module
	{
		public function __construct(
			string $title='ob_sfucator',
			string $label='<h1>Enable javascript to view content</h1>'
		){
			if(!class_exists('ob_sfucator'))
				require TK_LIB.'/ob_sfucator.php';

			ob_sfucator
			::	set_title($title)
			::	set_label($label);
		}

		public function exec($buffer)
		{
			return ob_sfucator::run($buffer);
		}
	}
	class ob_adapter_gzip implements ob_adapter_module
	{
		public function __construct()
		{
			if(!function_exists('gzencode'))
				throw new ob_adapter_exception(
					'zlib extension is not loaded'
				);

			header('Content-Encoding: gzip');
		}

		public function exec($buffer)
		{
			return gzencode($buffer);
		}
	}
	class ob_adapter_filecache implements ob_adapter_module
	{
		protected $output_file;
		protected $create_headers_cache=true;

		public static function invalidate(string $output_file)
		{
			if(file_exists($output_file.'__headers__'))
				unlink($output_file.'__headers__');

			if(file_exists($output_file))
				unlink($output_file);
		}

		public function __construct(
			string $output_file,
			string $ob_adapter_class
		){
			if(file_exists($output_file))
			{
				if(file_exists($output_file.'__headers__'))
					foreach(json_decode(
						file_get_contents(
							$output_file.'__headers__'
						),
						true
					) as $header)
						header($header);

				if(
					(!isset($_SERVER['HTTP_ACCEPT_ENCODING'])) ||
					(strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') === false)
				){
					if(in_array('Content-Encoding: gzip', headers_list()))
						header_remove('Content-Encoding');

					if(readgzfile($output_file) === false)
						readfile($output_file);
				}
				else
					readfile($output_file);

				return $ob_adapter_class::toggle_exit();
			}

			$dirname=dirname($output_file);

			if(!file_exists($dirname))
				mkdir($dirname, 0777, true);

			file_put_contents($output_file, '');
			$this->output_file=realpath($output_file);
		}

		public function exec($buffer)
		{
			if($this->create_headers_cache)
			{
				file_put_contents(
					$this->output_file.'__headers__',
					json_encode(
						array_diff(
							headers_list(),
							['Content-Encoding: gzip']
						),
						JSON_UNESCAPED_UNICODE
					)
				);

				$this->create_headers_cache=false;
			}

			file_put_contents(
				$this->output_file,
				$buffer,
				FILE_APPEND
			);

			return $buffer;
		}
	}
	class ob_adapter_gunzip implements ob_adapter_module
	{
		public function __construct()
		{
			if(function_exists('gzdecode'))
				return;

			throw new ob_adapter_exception(
				'zlib extension is not loaded'
			);
		}

		public function exec($buffer)
		{
			if(
				(!isset($_SERVER['HTTP_ACCEPT_ENCODING'])) ||
				(strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') !== false)
			){
				if(in_array('Content-Encoding: gzip', headers_list()))
					header_remove('Content-Encoding');

				$raw_buffer=gzdecode($buffer);

				if($raw_buffer !== false)
					return $raw_buffer;
			}

			return $buffer;
		}
	}
?>