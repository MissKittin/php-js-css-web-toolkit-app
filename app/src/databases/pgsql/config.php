<?php
	if(!defined('APP_STDLIB'))
		require __DIR__.'/../../../lib/stdlib.php';

	if(!file_exists(VAR_DB.'/pgsql'))
		mkdir(VAR_DB.'/pgsql');

	return [
		'db_type'=>'pgsql',
		'host'=>'127.0.0.1',
		'port'=>'5432',
		//'socket'=>'/var/run/postgresql',
		'db_name'=>'dbname',
		'charset'=>'UTF8';
		'user'=>'username',
		'password'=>'password',
		'seeded_path'=>VAR_DB.'/pgsql'
	];
?>