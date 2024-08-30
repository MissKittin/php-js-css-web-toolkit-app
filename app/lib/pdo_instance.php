<?php
	class pdo_instance
	{
		/*
		 * Add some features on top of pdo_connect
		 *
		 * Setting default database:
			pdo_instance(null, null, 'mydb');
		 *
		 * Usage:
			pdo_instance::set_default_db('my-default-db');
			$pdo_handler=pdo_instance::get('my-db', function($error){
				error_log('pdo_instance: '.$error->getMessage());
			});
			pdo_instance::close();
		 */

		protected static $pdo_handler=null;
		protected static $default_db=null;

		public static function set_default_db(string $db)
		{
			static::$default_db=$db;
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

			return static::$pdo_handler;
		}
		public static function close()
		{
			static::$pdo_handler=null;
		}
	}
?>