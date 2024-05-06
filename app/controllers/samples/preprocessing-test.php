<?php
	class preprocessed_cache
	{
		private $cache_file_handler;

		public static function main($view)
		{
			// ./var/lib/preprocessing-test/preprocessing-test.php won't be refreshed
			$view['cache_created']=false;

			if(!file_exists(VAR_LIB.'/preprocessing-test/preprocessing-test.php'))
			{
				if(!file_exists(VAR_LIB.'/preprocessing-test'))
					mkdir(VAR_LIB.'/preprocessing-test');

				$cache_object=new self(VAR_LIB.'/preprocessing-test/preprocessing-test.php');

				if(strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')
					$cache_object->push('$view[\'windows\']=true;');
				else
					$cache_object->push('$view[\'windows\']=false;');

				if(php_sapi_name() == 'cli-server')
					$cache_object->push('$view[\'builtin_server\']=true;');
				else
					$cache_object->push('$view[\'builtin_server\']=false;');

				$view['cache_created']=true;

				unset($cache_object);
			}

			require VAR_LIB.'/preprocessing-test/preprocessing-test.php';

			return $view;
		}

		public function __construct($output_file)
		{
			$this->cache_file_handler=fopen($output_file, 'w');
			fwrite($this->cache_file_handler, '<?php ');
		}
		public function __destruct()
		{
			fwrite($this->cache_file_handler, ' ?>');
			fclose($this->cache_file_handler);
		}

		public function push($input)
		{
			fwrite($this->cache_file_handler, $input);
		}
	}
?>