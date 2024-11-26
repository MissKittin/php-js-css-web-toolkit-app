<?php
	if(!defined('APP_STDLIB'))
		require __DIR__.'/../../../lib/stdlib.php';

	if(!file_exists(VAR_DB.'/mysql'))
		mkdir(VAR_DB.'/mysql');

	return [
		'db_type'=>'mysql',
		'host'=>'127.0.0.1',
		'port'=>'3306',
		//'socket'=>'/tmp/mysql.sock',
		'db_name'=>'dbname',
		'charset'=>'utf8mb4',
		'user'=>'username',
		'password'=>'password',
		'seeded_path'=>VAR_DB.'/mysql'
	];
?>