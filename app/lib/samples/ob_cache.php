<?php
	/*
	 * Checks if it is possible to connect to Redis
	 * if so, use Redis as cache, if not - dump the cache to a file
	 *
	 * Warning:
	 *  __DIR__./logger.php library is required
	 *
	 * See:
	 *  app/src/routes/samples/about.php
	 *  app/src/routes/samples/check-date.php
	 *  app/src/routes/samples/preprocessing-test.php
	 *  app/src/routes/samples/tk-test.php
	 *  app/src/routes/samples/ws-test.php
	 */

	require APP_LIB.'/ob_cache.php';
	require TK_LIB.'/ob_cache.php';

	trait ob_cache_redis_log
	{
		protected function log($message)
		{
			if(!function_exists('log_infos'))
				require __DIR__.'/logger.php';

			log_infos('app-library')->warn(__FILE__.': '.$message);
		}
	}

	class ob_cache_redis_mod extends ob_cache_redis
	{
		use ob_cache_redis_log;

		public function __construct()
		{
			if(!class_exists('Redis'))
				return;

			if(!function_exists('redis_connect'))
				require TK_LIB.'/redis_connect.php';

			$redis_handle=redis_connect(APP_DB.'/samples/redis');

			if($redis === false)
			{
				$this->log('Redis connection error');
				return;
			}

			parent::{__FUNCTION__}($redis_handle);
		}
	}
	class ob_cache_predis_mod extends ob_cache_predis
	{
		use ob_cache_redis_log;

		public function __construct()
		{
			if(app_env('REDIS_PREDIS') !== 'true')
				return;

			if(!function_exists('predis_connect_proxy'))
				require TK_LIB.'/predis_connect.php';

			try {
				parent::{__FUNCTION__}(
					predis_connect_proxy(APP_DB.'/samples/predis')
				);
			} catch(Predis\Connection\ConnectionException $error) {
				$this->log('Predis connection error: '.$error->getMessage());
				return;
			}

			if(!$this->activated)
				$this->log('Predis connection error: '.$error->getMessage());
		}
		public function exec($url, $expire): bool
		{
			try {
				return parent::{__FUNCTION__}($url, $expire);
			} catch(Predis\Connection\ConnectionException $error) {
				$this->log('Predis connection error: '.$error->getMessage());
				return false;
			}
		}
	}
	class ob_cache_file_mod extends ob_cache_file
	{
		public function __construct()
		{
			parent::{__FUNCTION__}(
				VAR_CACHE.'/ob_cache'
			);
		}
	}

	function ob_cache($url, $expire=3600)
	{
		// backward compatibility

		return ob_cache
		::	url($url)
		::	expire($expire)
		::	add(new ob_cache_predis_mod())
		::	add(new ob_cache_redis_mod())
		::	add(new ob_cache_file_mod())
		::	start();
	}
?>