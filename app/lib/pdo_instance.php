<?php
	class pdo_instance_exception extends Exception {}
	class pdo_instance
	{
		/*
		 * Add some features on top of pdo_connect
		 *
		 * Warning:
		 *  pdo_connect.php library is required
		 *
		 * Note:
		 *  sets PDO::ATTR_ERRMODE to PDO::ERRMODE_SILENT
		 *  throws an pdo_instance_exception on error
		 *
		 * Setting default database:
			pdo_instance::set_default_db('mydb'); // APP_DB/mydb
		 *
		 * Usage:
			pdo_instance::set_default_db('my-default-db'); // returns self
			pdo_instance::enable_exceptions(); // set PDO::ATTR_ERRMODE from PDO::ERRMODE_SILENT to PDO::ERRMODE_EXCEPTION, returns self
			pdo_instance::enable_seeder(); // enable automatic database seeder, returns self
			$pdo_handle=pdo_instance::get('my-db', function($error){
				my_log_function('pdo_instance: '.$error->getMessage());
			});
			pdo_instance::close();
		 */

		protected static $pdo_handle=null;
		protected static $default_db=null;
		protected static $pdo_exceptions=false;
		protected static $enable_seeder=false;

		public static function set_default_db(string $db)
		{
			static::$default_db=$db;
			return static::class;
		}
		public static function enable_exceptions()
		{
			static::$pdo_exceptions=true;
			return static::class;
		}
		public static function enable_seeder()
		{
			static::$enable_seeder=true;
			return static::class;
		}

		public static function get(
			?string $db=null,
			?callable $on_error=null
		){
			if(static::$pdo_handle !== null)
				return static::$pdo_handle;

			if($db === null)
			{
				if(static::$default_db === null)
					throw new pdo_instance_exception(
						'db parameter nor the default database is not set'
					);

				$db=static::$default_db;
			}

			if(!function_exists('pdo_connect'))
				require TK_LIB.'/pdo_connect.php';

			// PDO low level debugging (disable php_debugbar::get_collector('pdo')->addConnection() in model)
			//pdo_connect_bridge::set_class('PDO', function(...$arguments){
			//	return new class(...$arguments) extends PDO
			//	{
			//		public function __construct(...$arguments)
			//		{
			//			parent::__construct(...$arguments);
			//			php_debugbar
			//			::	get_collector('pdo')
			//			->	addConnection($this);
			//		}
			//	};
			//});

			if(!is_dir(APP_DB.'/'.$db))
				throw new pdo_instance_exception(
					APP_DB.'/'.$db.' not exists'
				);

			static::$pdo_handle=pdo_connect(
				APP_DB.'/'.$db,
				$on_error,
				static::$enable_seeder
			);

			if(static::$pdo_handle === false)
			{
				static::$pdo_handle=null;

				throw new pdo_instance_exception(
					'Connection to the database failed'
				);
			}

			if(static::$pdo_exceptions)
			{
				static::$pdo_handle->setAttribute(
					PDO::ATTR_ERRMODE,
					PDO::ERRMODE_EXCEPTION
				);

				return static::$pdo_handle;
			}

			static::$pdo_handle->setAttribute(
				PDO::ATTR_ERRMODE,
				PDO::ERRMODE_SILENT
			);

			return static::$pdo_handle;
		}
		public static function close()
		{
			static::$pdo_handle=null;
		}
	}
?>