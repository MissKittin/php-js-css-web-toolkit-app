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
	if(!function_exists('redis_connect'))
		require TK_LIB.'/redis_connect.php';

	function ob_cache($url, $expire=3600)
	{
		if($expire === 0)
			header('Cache-Control: public, max-age=31536000');

		if(getenv('REDIS_PREDIS') === 'true')
		{
			/*
			 * Predis is not fully compatible with phpredis:
			 * it returns null instead of false
			 * and you have to patch the ob_cache.php library
			 * which is so stupid that I'm fucking it!
			 * Don't be surprised if it stops working
			 * because I'll forget to fix this shit
			 */
			if(!file_exists(VAR_LIB.'/ob_cache_predis.php'))
				file_put_contents(
					VAR_LIB.'/ob_cache_predis.php',
					str_replace(
						[
							'<?php',
							'Exception',
							'if($cache_content === false)'
						],
						[
							'<?php namespace predis_ob_cache;',
							'\Exception',
							'if($cache_content === null)'
						],
						file_get_contents(TK_LIB.'/ob_cache.php')
					)
				);

			require VAR_LIB.'/ob_cache_predis.php';

			if(!class_exists('Predis\Client'))
				throw new Exception('Predis not installed. Use composer.phar require predis/predis');

			if(getenv('REDIS_IGNORE_ENV') === 'true')
				$db_getenv=function($env, $default_value)
				{
					return $default_value;
				};
			else
				$db_getenv=function($env, $default_value)
				{
					$value=getenv($env);

					if($value === false)
						return $default_value;

					return $value;
				};

			$predis_config=[
				'scheme'=>'tcp',
				'host'=>$db_getenv('REDIS_HOST', '127.0.0.1'),
				'port'=>(int)$db_getenv('REDIS_PORT', 6379),
				'database'=>(int)$db_getenv('REDIS_DBINDEX', 0)
			];

			if(
				(getenv('REDIS_IGNORE_ENV') !== 'true') &&
				(getenv('REDIS_SOCKET') !== false)
			){
				$predis_config['scheme']='unix';
				$predis_config['path']=getenv('REDIS_SOCKET');
				unset($predis_config['host']);
				unset($predis_config['port']);
			}

			$redis=new Predis\Client($predis_config);

			try{
				$redis->connect();

				if(predis_ob_cache\ob_redis_cache($redis, $url, $expire, true) === 0)
					exit();

				return true;
			} catch(Predis\Connection\ConnectionException $error) {
				error_log(__FILE__.': Predis connection error: '.$error->getMessage().' - using ob_file_cache');
			}
		}

		if(extension_loaded('redis'))
		{
			$redis=redis_connect(APP_DB.'/samples/redis');

			if($redis !== false)
			{
				if(ob_redis_cache($redis, $url, $expire, true) === 0)
					exit();

				return true;
			}

			error_log(__FILE__.': Redis connection error - using ob_file_cache');
		}

		if(ob_file_cache(VAR_CACHE.'/cache_'.$url, $expire, true) === 0)
			exit();

		return false;
	}
?>