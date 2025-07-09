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
		'db_type'=>'pgsql', // sqlite pgsql mysql
		'host'=>$db_getenv('PGSQL_HOST', '127.0.0.1'),
		'port'=>$db_getenv('PGSQL_PORT', '5432'),
		//'socket'=>'/var/run/postgresql',
		'db_name'=>$db_getenv('PGSQL_DBNAME', 'sampledb'),
		'charset'=>$db_getenv('PGSQL_CHARSET', 'UTF8'),
		'user'=>$db_getenv('PGSQL_USER', 'postgres'),
		'password'=>$db_getenv('PGSQL_PASSWORD', 'postgres'),
		//'seeded_path'=>$db
	];

	// socket has priority over the host/port
	if(
		(app_env('DB_IGNORE_ENV') !== 'true') &&
		(app_env('PGSQL_SOCKET') !== false)
	)
		$db_config['socket']=app_env('PGSQL_SOCKET');

	// you can implement the var/databases hierarchy
		if(!file_exists(VAR_DB.'/'.$db_config['db_type']))
			mkdir(VAR_DB.'/'.$db_config['db_type']);

		$db_config['seeded_path']=VAR_DB.'/'.$db_config['db_type'];

	return $db_config;
?>