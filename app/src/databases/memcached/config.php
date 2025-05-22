<?php
	return [
		[
			// SERVER #1
			'host'=>app_env('MEMCACHED_HOST', '127.0.0.1'),
			'port'=>(int)app_env('MEMCACHED_PORT', 11211),
			'socket'=>app_env('MEMCACHED_SOCKET', null) // 'socket'=>app_env('MEMCACHED_SOCKET', '/var/run/memcached/memcached.sock')
		]
	];
?>