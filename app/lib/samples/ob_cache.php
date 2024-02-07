<?php
	/*
	 * Checks if it is possible to connect to Redis
	 * if so, use Redis as cache, if not - dump the cache to a file
	 *
	 * See:
	 *  controllers/samples/about.php
	 *  controllers/samples/check-date.php
	 *  controllers/samples/preprocessing-test.php
	 */

	if(!function_exists('ob_file_cache'))
		require TK_LIB.'/ob_cache.php';
	if(!function_exists('redis_connect'))
		require TK_LIB.'/redis_connect.php';

	function ob_cache($url, $expire=3600)
	{
		if(extension_loaded('redis'))
		{
			$redis=redis_connect(APP_DB.'/samples/redis');

			if($redis)
			{
				if(ob_redis_cache($redis, $url, $expire, true) === 0)
					exit();

				return true;
			}
		}

		if(ob_file_cache(VAR_CACHE.'/cache_'.$url, $expire, true) === 0)
			exit();

		return false;
	}
?>