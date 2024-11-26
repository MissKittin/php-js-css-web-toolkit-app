<?php
	/*
	 * A modular overlay for functions from the ob_cache.php library
	 * For more info see ob_cache.php library
	 *
	 * Modules:
	 *  ob_cache_redis - adapter for ob_redis_cache()
	 *   new ob_cache_redis($redis_handle)
	 *  ob_cache_predis - adapter for ob_redis_cache()- version for Predis
	 *   new ob_cache_predis($predis_handle)
	 *  ob_cache_predis_silent - adapter for ob_redis_cache()- version for Predis with try/catch
	 *   new ob_cache_predis_silent($predis_handle)
	 *  ob_cache_memcached - adapter for ob_memcached_cache()
	 *   new ob_cache_memcached($memcached_handle)
	 *  ob_cache_file - adapter for ob_file_cache()
	 *   new ob_cache_file('path/to/cache_dir')
	 *   note: if the specified directory does not exist, it will be created
	 *
	 * Usage:
		if(ob_cache
		::	url('string_file_name_from_url') // required
		::	expire(int_seconds) // optional, default: 0 (permanent cache)
		::	add(new ob_cache_mod_a())
		::	add(new ob_cache_mod_b())
		::	start())
			exit();
	 */

	interface ob_cache_module
	{
		public function exec($url, $expire): bool;
	}

	class ob_cache
	{
		protected static $expire=0;
		protected static $url=null;
		protected static $instances=[];

		public static function expire(int $expire)
		{
			static::$expire=$expire;
			return static::class;
		}
		public static function url(string $url)
		{
			static::$url=$url;
			return static::class;
		}
		public static function add(ob_cache_module $instance)
		{
			static::$instances[]=$instance;
			return static::class;
		}
		public static function start()
		{
			if(static::$url === null)
				throw new app_exception(
					'url is not set'
				);

			if(static::$expire === 0)
				header('Cache-Control: public, max-age=31536000');

			foreach(static::$instances as $instance)
				if($instance->exec(
					static::$url,
					static::$expire
				))
					return true;

			return false;
		}
	}

	class ob_cache_redis implements ob_cache_module
	{
		protected $activated=false;
		protected $redis_handle;

		public function __construct($redis_handle)
		{
			if($redis_handle !== false)
				return;

			if(!function_exists('ob_redis_cache'))
				require TK_LIB.'/ob_cache.php';

			$this->activated=true;
			$this->redis_handle=$redis_handle;
		}

		public function exec($url, $expire): bool
		{
			if(!$this->activated)
				return false;

			return (ob_redis_cache(
				$this->redis_handle,
				'cache_'.$url,
				$expire,
				true,
				'ob_cache_'
			) === 0);
		}
	}
	class ob_cache_predis extends ob_cache_redis
	{
		public function __construct($redis_handle)
		{
			$redis_handle->connect();
			parent::{__FUNCTION__}($redis_handle);
		}
	}
	class ob_cache_predis_silent extends ob_cache_predis
	{
		public function __construct($redis_handle)
		{
			try {
				parent::{__FUNCTION__}($redis_handle);
			} catch(Predis\Connection\ConnectionException $error) {
				return;
			}
		}

		public function exec($url, $expire): bool
		{
			try {
				return parent::{__FUNCTION__}($url, $expire);
			} catch(Predis\Connection\ConnectionException $error) {
				return false;
			}
		}
	}
	class ob_cache_memcached implements ob_cache_module
	{
		protected $activated=false;
		protected $memcached_handle;

		public function __construct($memcached_handle)
		{
			if(!function_exists('ob_memcached_cache'))
				require TK_LIB.'/ob_cache.php';

			$this->activated=true;
			$this->memcached_handle=$memcached_handle;
		}

		public function exec($url, $expire): bool
		{
			if(!$this->activated)
				return false;

			return (ob_memcached_cache(
				$this->memcached_handle,
				'cache_'.$url,
				$expire,
				true,
				'ob_cache_'
			) === 0);
		}
	}
	class ob_cache_file implements ob_cache_module
	{
		protected $cache_dir;

		public function __construct(string $cache_dir)
		{
			if(!function_exists('ob_file_cache'))
				require TK_LIB.'/ob_cache.php';

			if(!file_exists($cache_dir)
				mkdir($cache_dir, 0777, true);

			$this->cache_dir=$cache_dir;
		}

		public function exec($url, $expire): bool
		{
			return (ob_file_cache(
				$this->cache_dir.'/cache_'.$url,
				$expire,
				true
			) === 0);
		}
	}
?>