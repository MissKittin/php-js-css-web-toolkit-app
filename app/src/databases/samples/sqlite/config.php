<?php
	if(!defined('APP_STDLIB'))
		require __DIR__.'/../../../../lib/stdlib.php';

	if(app_env('DB_IGNORE_ENV') === 'true')
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
		'db_type'=>'sqlite', // sqlite pgsql mysql
		'host'=>$db_getenv('SQLITE_PATH', $db.'/database.sqlite3')
		//'port'=>'',
		//,'socket'=>'',
		//'db_name'=>'',
		//'charset'=>'',
		//'user'=>'',
		//'password'=>'',
		//'seeded_path'=>$db
	];

	if(app_env('SQLITE_PATH') === false)
	{
		// you can implement the var/databases hierarchy

		if(!file_exists(VAR_DB.'/'.$db_config['db_type']))
			mkdir(VAR_DB.'/'.$db_config['db_type']);

		$db_config['host']=VAR_DB.'/'.$db_config['db_type'].'/database.sqlite3';
		//$db_config['seeded_path']=VAR_DB.'/'.$db_config['db_type'];
	}

	return $db_config;
?>