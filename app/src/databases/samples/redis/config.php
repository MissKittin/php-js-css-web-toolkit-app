<?php
	if(app_env('REDIS_IGNORE_ENV') === 'true')
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
		'host'=>$db_getenv('REDIS_HOST', '127.0.0.1'),
		'port'=>(int)$db_getenv('REDIS_PORT', 6379),
		'dbindex'=>(int)$db_getenv('REDIS_DBINDEX', 0)
	];

	// socket has priority over the host/port
	if(
		(app_env('REDIS_IGNORE_ENV') !== 'true') &&
		(app_env('REDIS_SOCKET') !== false)
	)
		$db_config['socket']=app_env('REDIS_SOCKET');

	return $db_config;
?>