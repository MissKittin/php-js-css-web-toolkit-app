<?php
	class redis_instance_exception extends Exception {}
	class redis_instance
	{
		/*
		 * Combine redis_connect and predis_connect
		 *
		 * Warning:
		 *  predis_connect.php library is required
		 *  redis_connect.php library is required
		 *
		 * Note:
		 *  PHPRedis takes precedence over Predis
		 *  throws an redis_instance_exception on error
		 *
		 * Setting default database:
			redis_instance
			::	set_default_db_redis('mydb-redis') // APP_DB/mydb-redis
			::	set_default_db_predis('mydb-predis'); // APP_DB/mydb-predis
		 *
		 * Usage:
			redis_instance::set_default_db_redis('my-default-db-redis'); // returns self
			redis_instance::set_default_db_predis('my-default-db-predis'); // returns self
			// note: if Predis is used, a predis_phpredis_proxy instance will be returned instead of Predis\Client
			$redis_handle=redis_instance::get('my-db-redis', 'my-db-predis', function($error){
				// not used by predis_connect()
				my_log_function('redis_instance: '.$error->getMessage());
			});
			redis_instance::is_predis(); // returns bool
			$predis_handle=redis_instance::get_raw_predis(); // returns a Predis\Client instance or null
			redis_instance::close();
		 */

		protected static $predis_handle=null;
		protected static $redis_handle=null;
		protected static $default_db_redis=null;
		protected static $default_db_predis=null;

		protected static function get_redis(
			$db_redis,
			$on_error
		){
			if($db_redis === null)
			{
				if(static::$default_db_redis === null)
					throw new redis_instance_exception(
						'db_redis parameter nor the default Redis database is not set'
					);

				$db_redis=static::$default_db_redis;
			}

			if(!function_exists('redis_connect'))
				require TK_LIB.'/redis_connect.php';

			if(!is_dir(APP_DB.'/'.$db_redis))
				throw new redis_instance_exception(
					APP_DB.'/'.$db_redis.' does not exist'
				);

			static::$redis_handle=redis_connect(
				APP_DB.'/'.$db_redis,
				$on_error
			);

			if(static::$redis_handle === false)
			{
				static::$redis_handle=null;

				throw new redis_instance_exception(
					'Connection to the Redis failed'
				);
			}

			return static::$redis_handle;
		}
		protected static function get_predis(
			$db_predis,
			$on_error
		){
			if($db_predis === null)
			{
				if(static::$default_db_predis === null)
					throw new redis_instance_exception(
						'db_predis parameter nor the default Predis database is not set'
					);

				$db_predis=static::$default_db_predis;
			}

			if(!function_exists('predis_connect'))
				require TK_LIB.'/predis_connect.php';

			if(!is_dir(APP_DB.'/'.$db_predis))
				throw new redis_instance_exception(
					APP_DB.'/'.$db_predis.' does not exist'
				);

			static::$predis_handle=predis_connect(
				APP_DB.'/'.$db_predis
			);

			static::$redis_handle=new predis_phpredis_proxy(
				static::$predis_handle
			);

			return static::$redis_handle;
		}

		public static function set_default_db_redis(string $db)
		{
			static::$default_db_redis=$db;
			return static::class;
		}
		public static function set_default_db_predis(string $db)
		{
			static::$default_db_predis=$db;
			return static::class;
		}

		public static function get(
			?string $db_redis=null,
			?string $db_predis=null,
			?callable $on_error=null
		){
			if(static::$redis_handle !== null)
				return static::$redis_handle;

			if(class_exists('Redis'))
			{
				return static::get_redis(
					$db_redis,
					$on_error
				);
			}

			if(class_exists('\Predis\Client'))
			{
				return static::get_predis(
					$db_predis,
					$on_error
				);
			}

			throw new redis_instance_exception(''
			.	'Redis extension is not loaded '
			.	'nor Predis/Predis package is installed'
			);
		}
		public static function is_predis()
		{
			if(static::$predis_handle === null)
				return false;

			return true;
		}
		public static function get_raw_predis()
		{
			return static::$predis_handle;
		}
		public static function close()
		{
			static::$redis_handle=null;
			static::$predis_handle=null;
		}
	}
?>