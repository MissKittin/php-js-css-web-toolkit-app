<?php
	//namespace app\src\models;

	//use pdo_instance;
	//use redis_connect;
	//use predis_connect;
	//use memcached_connect;
	//use cache_container;
	//use cache_driver_pdo;
	//use cache_driver_redis;
	//use cache_driver_memcached;
	//use cache_driver_apcu;
	//use php_debugbar;

	class cache_model_template
	{
		//protected $database='sqlite'; // PDO
		//protected $database=null; // PDO default
		protected $database=APP_DB.'/redis';

		protected $model_params=null;
		protected $connected=false;
		//protected $pdo_handle=null;
		//protected $redis_handle=null;
		//protected $memcached_handle=null;
		//protected $cache_container=null;

		public function __construct($model_params)
		{
			$this->model_params=$model_params;

			//
		}

		protected function connect_pdo()
		{
			if(!class_exists('pdo_instance'))
				require APP_LIB.'/pdo_instance.php';

			$this->pdo_handle=pdo_instance::get(
				$this->database,
				function($error)
				{
					// $error->getMessage();
				}
			);

			// alternative if you use dependency injection
			//$this->pdo_handle=app_ioc('PDO');

			php_debugbar::get_collector('pdo')->addConnection($this->pdo_handle);
		}
		protected function connect_redis()
		{
			if(!function_exists('redis_connect'))
				require TK_LIB.'/redis_connect.php';

			$this->redis_handle=redis_connect(
				$this->database
			);
		}
		protected function connect_predis()
		{
			if(!function_exists('predis_connect'))
				require TK_LIB.'/predis_connect.php';

			$this->redis_handle=predis_connect_proxy(
				$this->database
			);
		}
		protected function connect_memcached()
		{
			if(!function_exists('memcached_connect'))
				require TK_LIB.'/memcached_connect.php';

			if(
				(!class_exists('Memcached')) &&
				class_exists('\Clickalicious\Memcached\Client')
			)
				require TK_LIB.'/clickalicious_memcached.php';

			$this->memcached_handle=memcached_connect(
				$this->database
			);
		}

		protected function connect()
		{
			if($this->connected)
				return;

			//$this->connect_pdo();
			//$this->connect_redis();
			//$this->connect_predis();
			//$this->connect_memcached();

			//if(!class_exists('cache_container'))
			//	require TK_LIB.'/cache_container.php';

			//$this->cache_container=new cache_container(new cache_driver_pdo([
			//	'pdo_handle'=>$this->pdo_handle,
			//	'table_name'=>'cache',
			//	'create_table'=>false
			//]));

			//$this->cache_container=new cache_container(new cache_driver_redis([
			//	'redis_handle'=>$this->redis_handle,
			//	'prefix'=>'cache__'
			//]));

			//$this->cache_container=new cache_container(new cache_driver_memcached([
			//	'memcached_handle'=>$this->memcached_handle,
			//	'prefix'=>'cache__'
			//]));

			//$this->cache_container=new cache_container(new cache_driver_apcu([
			//	'prefix'=>'cache__'
			//]));

			$this->connected=true;
		}
		protected function disconnect()
		{
			if(!$this->connected)
				return;

			//$this->cache_container=null;
			//$this->pdo_handle=null;
			//$this->redis_handle=null;
			//$this->memcached_handle=null;

			//pdo_instance::close();

			$this->connected=false;
		}

		//
	}
?>