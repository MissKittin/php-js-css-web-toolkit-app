<?php
	if(!defined('APP_STDLIB'))
		require __DIR__.'/../../../lib/stdlib.php';

	$_cache=basename(__DIR__);

	if(!defined('APP_STDLIB'))
		require __DIR__.'/../../../lib/stdlib.php';

	if(!file_exists(VAR_DB.'/'.$_cache))
		mkdir(VAR_DB.'/'.$_cache);

	return [
		'db_type'=>'mysql',
		'host'=>app_env('MYSQL_HOST', '127.0.0.1'),
		'port'=>app_env('MYSQL_PORT', '3306'),
		'socket'=>app_env('MYSQL_SOCKET', null), // 'socket'=>app_env('MYSQL_SOCKET', '/var/run/mysqld/mysqld.sock'),
		'db_name'=>app_env('MYSQL_DBNAME', 'dbname'),
		'charset'=>app_env('MYSQL_CHARSET', 'utf8mb4'),
		'user'=>app_env('MYSQL_USER', 'username'),
		'password'=>app_env('MYSQL_PASSWORD', 'password'),
		'seeded_path'=>VAR_DB.'/'.$_cache
	];
?>