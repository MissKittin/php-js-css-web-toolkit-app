<?php
	if(!defined('APP_STDLIB'))
		require __DIR__.'/../../../lib/stdlib.php';

	if(!file_exists(VAR_DB.'/sqlite'))
		mkdir(VAR_DB.'/sqlite');

	return [
		'db_type'=>'sqlite',
		'host'=>VAR_DB.'/sqlite/database.sqlite3'
	];
?>