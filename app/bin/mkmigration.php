<?php
	// Create new migration from template

	if(!isset($argv[2]))
	{
		echo 'Usage: '.$argv[0].' database-name migration-name'.PHP_EOL;
		exit(1);
	}

	if(!defined('APP_STDLIB'))
		require __DIR__.'/../lib/stdlib.php';

	$mysql_id_option='					//	]';
	$db_name=APP_DB.'/'.$argv[1];
	$migration_name=date('Y-m-d_H-i-s').'_'.$argv[2].'.php';

	if(!file_exists(
		$db_name.'/config.php'
	)){
		echo 'Error: '.$argv[1].'/config.php does not exists'.PHP_EOL;
		exit(1);
	}

	if(!is_dir(
		$db_name.'/migrations'
	)){
		echo 'Error: '.realpath($db_name).DIRECTORY_SEPARATOR.'migrations is not a directory'.PHP_EOL;
		exit(1);
	}

	foreach(
		scandir($db_name.'/migrations')
		as $migration
	){
		if(substr(
			$migration,
			-(strlen($argv[2])+5)
		) === '_'.$argv[2].'.php'){
			echo 'Error: *_'.$argv[2].'.php already exists'.PHP_EOL;
			exit(1);
		}
	}

	$db_params=require $db_name.'/config.php';

	if(!isset($db_params['db_type']))
	{
		echo 'Error: db_type is not specified in config.php'.PHP_EOL;
		exit(1);
	}

	switch($db_params['db_type'])
	{
		case 'pgsql':
			$migration_id='SERIAL PRIMARY KEY';
		break;
		case 'mysql':
			$migration_id='INTEGER NOT NULL AUTOINCREMENT';
			$mysql_id_option=''
			.'					//	],'."\n"
			.'					//	\'id\''
			;
		break;
		case 'sqlite':
			$migration_id='INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL';
		break;
		default:
			echo 'Error: unknown database type'.PHP_EOL;
			exit(1);
	}

	if(file_put_contents($db_name.'/migrations/'.$migration_name, '<?php'."\n"
	.'	//use app\src\controllers\controller_template;'."\n"
	."\n"
	.'	//if(!class_exists(\'pdo_crud_builder\'))'."\n"
	.'	//	require TK_LIB.\'/pdo_crud_builder.php\';'."\n"
	."\n"
	.'	if(!class_exists(\'pdo_cheat\'))'."\n"
	.'		require TK_LIB.\'/pdo_cheat.php\';'."\n"
	."\n"
	.'	if(!class_exists(\'controller_template\'))'."\n"
	.'		require APP_CTRL.\'/controller_template.php\';'."\n"
	."\n"
	.'	return function($pdo_handle, $mode)'."\n"
	.'	{'."\n"
	.'		//$crud_builder=new pdo_crud_builder(['."\n"
	.'		//	\'pdo_handle\'=>$pdo_handle'."\n"
	.'		//]);'."\n"
	."\n"
	.'		$pdo_cheat=new pdo_cheat(['."\n"
	.'			\'pdo_handle\'=>$pdo_handle,'."\n"
	.'			\'table_name\'=>controller_template::model_params()[\'table_name\']'."\n"
	.'		]);'."\n"
	."\n"
	.'		try {'."\n"
	.'			switch($mode)'."\n"
	.'			{'."\n"
	.'				case \'apply\':'."\n"
	.'					//if(!$crud_builder->create_table('."\n"
	.'					//	controller_template::model_params()[\'table_name\'],'."\n"
	.'					//	['."\n"
	.'					//		\'id\'=>\''.$migration_id.'\','."\n"
	.'					//		\'column_a\'=>\'TYPE\''."\n"
	.						$mysql_id_option."\n"
	.'					//)->exec())'."\n"
	.'					//	return false;'."\n"
	."\n"
	.'					if(!$pdo_cheat->new_table()'."\n"
	.'					->	id(pdo_cheat::default_id_type)'."\n"
	.'					->	column_a(\'TYPE\')'."\n"
	.'					->	save_table())'."\n"
	.'						return false;'."\n"
	."\n"
	.'					//'."\n"
	."\n"
	.'					return true;'."\n"
	.'				case \'rollback\':'."\n"
	.'					//if(!$crud_builder->drop_table('."\n"
	.'					//	controller_template::model_params()[\'table_name\']'."\n"
	.'					//)->exec())'."\n"
	.'					//	return false;'."\n"
	."\n"
	.'					if(!$pdo_cheat->clear_table()->drop_table())'."\n"
	.'						return false;'."\n"
	."\n"
	.'					//'."\n"
	."\n"
	.'					return true;'."\n"
	.'			}'."\n"
	.'		} catch(PDOException $error) {'."\n"
	.'			//'."\n"
	."\n"
	.'			return false;'."\n"
	.'		}'."\n"
	.'	};'."\n"
	.'?>'
	) === false){
		echo 'Error: saving to '.$migration_name.' failed'.PHP_EOL;
		exit(1);
	}

	echo $migration_name.PHP_EOL;
?>