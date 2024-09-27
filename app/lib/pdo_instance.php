<?php
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
		 *
		 * Setting default database:
			pdo_instance::set_default_db('mydb'); // APP_DB/mydb
		 *
		 * Usage:
			pdo_instance::set_default_db('my-default-db'); // returns self
			pdo_instance::enable_exceptions(); // set PDO::ATTR_ERRMODE from PDO::ERRMODE_SILENT to PDO::ERRMODE_EXCEPTION, returns self
			$pdo_handler=pdo_instance::get('my-db', function($error){
				my_log_function('pdo_instance: '.$error->getMessage());
			});
			pdo_instance::close();
		 */

		protected static $pdo_handler=null;
		protected static $default_db=null;
		protected static $pdo_exceptions=false;

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

		public static function get(
			?string $db=null,
			?callable $on_error=null
		){
			if(static::$pdo_handler !== null)
				return static::$pdo_handler;

			if($db === null)
			{
				if(static::$default_db === null)
					throw new app_exception('db parameter nor the default database is not set');

				$db=static::$default_db;
			}

			if(!function_exists('pdo_connect'))
				require TK_LIB.'/pdo_connect.php';

			if(!is_dir(APP_DB.'/'.$db))
				throw new app_exception(APP_DB.'/'.$db.' not exists');

			static::$pdo_handler=pdo_connect(
				APP_DB.'/'.$db,
				$on_error
			);

			if(static::$pdo_handler === false)
				throw new app_exception('Connection to the database failed');

			if(static::$pdo_exceptions)
				static::$pdo_handler->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			else
				static::$pdo_handler->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);

			return static::$pdo_handler;
		}
		public static function close()
		{
			static::$pdo_handler=null;
		}
	}
?>