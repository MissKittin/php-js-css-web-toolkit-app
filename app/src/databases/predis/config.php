<?php
	if(app_env('REDIS_SOCKET') === false)
		return [
			'scheme'=>'tcp',
			'host'=>app_env('REDIS_HOST', '127.0.0.1'),
			'port'=>(int)app_env('REDIS_PORT', 6379),
			'database'=>(int)app_env('REDIS_DBINDEX', 0)
		];

	return [
		'scheme'=>'unix',
		'path'=>app_env('REDIS_SOCKET'), // 'path'=>app_env('REDIS_SOCKET', '/var/run/redis/redis.sock')
		'database'=>(int)app_env('REDIS_DBINDEX', 0)
	];
?>