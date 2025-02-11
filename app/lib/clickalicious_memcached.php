<?php
	/*
	 * Memcached polyfill - memcached.php proxy
	 *
	 * Warning:
	 *  you can only connect to the first server, the rest will be ignored
	 *  clickalicious/memcached.php package is required
	 *
	 * Note:
	 *  throws an clickalicious_memcached_exception on error
	 */

	if(class_exists('Memcached'))
		return false;

	class clickalicious_memcached_exception extends Exception {}

	if(!class_exists('\Clickalicious\Memcached\Client'))
		throw new clickalicious_memcached_exception(
			'clickalicious/memcached.php package is not installed'
		);

	class Memcached extends Clickalicious\Memcached\Client
	{
		protected $polyfill_persistent_id;
		protected $polyfill_server_defined=false;

		public function __construct($persistent_id)
		{
			$this->polyfill_persistent_id=$persistent_id;
		}

		public function addServer($host, $port, $weight)
		{
			if($this->polyfill_server_defined)
				return true;

			parent::__construct(
				$host,
				$port,
				$this->polyfill_persistent_id
			);

			$this->polyfill_server_defined=true;

			return true;
		}
	}
?>