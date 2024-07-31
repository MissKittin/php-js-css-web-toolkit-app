<?php
	if(app_env::getenv('MEMCACHED_IGNORE_ENV') === 'true')
		$db_getenv=function($env, $default_value)
		{
			return $default_value;
		};
	else
		$db_getenv=function($env, $default_value)
		{
			$value=app_env::getenv($env);

			if($value === false)
				return $default_value;

			return $value;
		};

	$db_config=[[
		'host'=>$db_getenv('MEMCACHED_HOST', '127.0.0.1'),
		'port'=>(int)$db_getenv('MEMCACHED_PORT', 11211)
	]];

	// socket has priority over the host/port
	if(
		(getenv('MEMCACHED_IGNORE_ENV') !== 'true') &&
		(getenv('MEMCACHED_SOCKET') !== false)
	)
		$db_config[0]['socket']=getenv('MEMCACHED_SOCKET');

	return $db_config;
?>