<?php
	if(app_env::getenv('REDIS_IGNORE_ENV') === 'true')
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

	$db_config=[
		'scheme'=>'tcp',
		'host'=>$db_getenv('REDIS_HOST', '127.0.0.1'),
		'port'=>(int)$db_getenv('REDIS_PORT', 6379),
		'database'=>(int)$db_getenv('REDIS_DBINDEX', 0)
	];

	// socket has priority over the host/port
	if(
		(app_env::getenv('REDIS_IGNORE_ENV') !== 'true') &&
		(app_env::getenv('REDIS_SOCKET') !== false)
	){
		$predis_config['scheme']='unix';
		$predis_config['path']=app_env::getenv('REDIS_SOCKET');
		unset($predis_config['host']);
		unset($predis_config['port']);
	}

	return $db_config;
?>