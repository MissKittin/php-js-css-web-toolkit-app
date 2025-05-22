<?php
	$_cache['app_env']=app_env(
		'SQLITE_PATH',
		VAR_DB.'/'.basename(__DIR__).'/database.sqlite3'
	);
	$_cache['dirname']=dirname(
		$_cache['app_env']
	);

	if(!defined('APP_STDLIB'))
		require __DIR__.'/../../../lib/stdlib.php';

	if(!file_exists($_cache['dirname']))
		mkdir($_cache['dirname']);

	return [
		'db_type'=>'sqlite',
		'host'=>$_cache['app_env'],
		'seeded_path'=>$_cache['dirname']
	];
?>