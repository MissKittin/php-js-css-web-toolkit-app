<?php
	if(!function_exists('app_db_migrate_log'))
		require APP_LIB.'/samples/app_db_migrate_log.php';

	app_db_migrate_log([
		'pdo_handle'=>$pdo_handle, // this is an internal PDO instance derived from pdo_connect()
		'directory'=>__DIR__.'/migrations'
	]);

?>