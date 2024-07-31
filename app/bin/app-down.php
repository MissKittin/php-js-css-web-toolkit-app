<?php
	// Break technically

	require __DIR__.'/../lib/stdlib.php';

	if(!file_exists(APP_VIEW.'/maintenance_info.html'))
	{
		echo 'Create '.realpath(APP_VIEW).DIRECTORY_SEPARATOR.'maintenance_info.html'.PHP_EOL;
		exit(1);
	}

	if(!isset($argv[1]))
	{
		echo 'Usage: '.$argv[0].' up|down|status'.PHP_EOL;
		exit(1);
	}

	switch($argv[1])
	{
		case 'up';
			if(!file_exists(VAR_DIR.'/APP_IS_DOWN'))
			{
				echo 'App is already up'.PHP_EOL;
				exit(1);
			}

			unlink(VAR_DIR.'/APP_IS_DOWN');
		break;
		case 'down':
			if(file_exists(VAR_DIR.'/APP_IS_DOWN'))
			{
				echo 'App is already down'.PHP_EOL;
				exit(1);
			}

			copy(APP_VIEW.'/maintenance_info.html', VAR_DIR.'/APP_IS_DOWN');
		break;
		case 'status':
			if(file_exists(VAR_DIR.'/APP_IS_DOWN'))
			{
				echo 'App is down'.PHP_EOL;
				exit(1);
			}

			echo 'App is up'.PHP_EOL;
		break;
		default:
			echo 'Usage: '.$argv[0].' up|down'.PHP_EOL;
			exit(1);
	}
?>