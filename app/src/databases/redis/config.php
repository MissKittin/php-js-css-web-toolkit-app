<?php
	return [
		'host'=>app_env('REDIS_HOST', '127.0.0.1'),
		'port'=>(int)app_env('REDIS_PORT', 6379),
		'socket'=>app_env('REDIS_SOCKET', null), // 'socket'=>app_env('REDIS_SOCKET', '/var/run/redis/redis.sock'),
		'dbindex'=>(int)app_env('REDIS_DBINDEX', 0)
	];
?>