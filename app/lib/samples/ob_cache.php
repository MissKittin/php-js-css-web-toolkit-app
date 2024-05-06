<?php
	/*
	 * Checks if it is possible to connect to Redis
	 * if so, use Redis as cache, if not - dump the cache to a file
	 *
	 * See:
	 *  routes/samples/about.php
	 *  routes/samples/check-date.php
	 *  routes/samples/preprocessing-test.php
	 */

	if(!function_exists('ob_file_cache'))
		require TK_LIB.'/ob_cache.php';

	function ob_cache($url, $expire=3600)
	{
		if($expire === 0)
			header('Cache-Control: public, max-age=31536000');

		if(getenv('REDIS_PREDIS') === 'true')
		{
			if(!function_exists('predis_connect_proxy'))
				require TK_LIB.'/predis_connect.php';

			$redis=predis_connect_proxy(APP_DB.'/samples/predis');

			try{
				$redis->connect();

				if(ob_redis_cache($redis, $url, $expire, true) === 0)
					exit();

				return true;
			} catch(Predis\Connection\ConnectionException $error) {
				error_log(__FILE__.': Predis connection error: '.$error->getMessage().' - using ob_file_cache');
			}
		}

		if(extension_loaded('redis'))
		{
			if(!function_exists('redis_connect'))
				require TK_LIB.'/redis_connect.php';

			$redis=redis_connect(APP_DB.'/samples/redis');

			if($redis !== false)
			{
				if(ob_redis_cache($redis, $url, $expire, true) === 0)
					exit();

				return true;
			}

			error_log(__FILE__.': Redis connection error - using ob_file_cache');
		}

		if(ob_file_cache(VAR_CACHE.'/ob_cache/cache_'.$url, $expire, true) === 0)
			exit();

		return false;
	}
?>