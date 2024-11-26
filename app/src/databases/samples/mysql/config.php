<?php
	if(!defined('APP_STDLIB'))
		require __DIR__.'/../../../../lib/stdlib.php';

	if(app_env('DB_IGNORE_ENV') === 'true')
		$db_getenv=function($env, $default_value)
		{
			return $default_value;
		};
	else
		$db_getenv=function($env, $default_value)
		{
			return app_env($env, $default_value);
		};

	$db_config=[
		'db_type'=>'mysql', // sqlite pgsql mysql
		'host'=>$db_getenv('MYSQL_HOST', '[::1]'),
		'port'=>$db_getenv('MYSQL_PORT', '3306'),
		//'socket'=>'/tmp/mysql.sock',
		'db_name'=>$db_getenv('MYSQL_DBNAME', 'sampledb'),
		'charset'=>$db_getenv('MYSQL_CHARSET', 'utf8mb4'),
		'user'=>$db_getenv('MYSQL_USER', 'root'),
		'password'=>$db_getenv('MYSQL_PASSWORD', ''),
		//'seeded_path'=>$db
	];

	// socket has priority over the host/port
	if(
		(app_env('DB_IGNORE_ENV') !== 'true') &&
		(app_env('MYSQL_SOCKET') !== false)
	)
		$db_config['socket']=app_env('MYSQL_SOCKET');

	// you can implement the var/databases hierarchy
		if(!file_exists(VAR_DB.'/'.$db_config['db_type']))
			mkdir(VAR_DB.'/'.$db_config['db_type']);

		$db_config['seeded_path']=VAR_DB.'/'.$db_config['db_type'];

	return $db_config;
?>