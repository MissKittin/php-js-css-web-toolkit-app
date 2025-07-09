<?php
	if(!defined('APP_STDLIB'))
		require __DIR__.'/../../../lib/stdlib.php';

	$_cache=basename(__DIR__);

	if(!defined('APP_STDLIB'))
		require __DIR__.'/../../../lib/stdlib.php';

	if(!file_exists(VAR_DB.'/'.$_cache))
		mkdir(VAR_DB.'/'.$_cache);

	return [
		'db_type'=>'pgsql',
		'host'=>app_env('PGSQL_HOST', '127.0.0.1'),
		'port'=>app_env('PGSQL_PORT', '5432'),
		'socket'=>app_env('PGSQL_SOCKET', null), // 'socket'=>app_env('PGSQL_SOCKET', '/var/run/postgresql'),
		'db_name'=>app_env('PGSQL_DBNAME', 'dbname'),
		'charset'=>app_env('PGSQL_CHARSET', 'UTF8'),
		'user'=>app_env('PGSQL_USER', 'username'),
		'password'=>app_env('PGSQL_PASSWORD', 'password'),
		'seeded_path'=>VAR_DB.'/'.$_cache
	];
?>