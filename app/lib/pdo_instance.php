<?php
	function pdo_instance(
		string $db=null,
		callable $on_error=null,
		string $set_default_db=null
	){
		/*
		 * Add some features on top of pdo_connect
		 *
		 * Setting default database:
			pdo_instance(null, null, 'mydb');
		 *
		 * Usage:
			$pdo_handler=pdo_instance('mydb');
			$pdo_handler=pdo_instance('mydb', function($error){
				error_log('pdo_instance: '.$error->getMessage());
			});
		 */

		static $pdo_handler=null;
		static $default_db=null;

		if($pdo_handler !== null)
			return $pdo_handler;

		if($set_default_db !== null)
		{
			$default_db=$set_default_db;
			return;
		}

		if($db === null)
		{
			if($default_db === null)
				throw new app_exception('db parameter is not set and the default database is not set');

			$db=$default_db;
		}

		if(!function_exists('pdo_connect'))
			require TK_LIB.'/pdo_connect.php';

		if(!is_dir(APP_DB.'/'.$db))
			throw new app_exception(APP_DB.'/'.$db.' not exists');

		$pdo_handler=pdo_connect(
			APP_DB.'/'.$db,
			$on_error
		);

		if($pdo_handler === false)
			throw new app_exception('Connection to the database failed');

		return $pdo_handler;
	}
?>