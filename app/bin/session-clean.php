<?php
	/*
	 * Remove stale sessions
	 *
	 * Note:
	 *  you can call this tool from the main application
	 *  edit this script if you use other modules
	 *   that require manual cleaning
	 */

	if(!defined('APP_STDLIB'))
		require __DIR__.'/../lib/stdlib.php';

	if(!class_exists('app_session'))
		require APP_LIB.'/app_session.php';

	return (function($modules){
		$failed=false;
		$log=[];
		$errors=[];

		foreach($modules as $module=>$params)
			try {
				$module::session_clean(...$params);
			} catch(app_session_exception $error) {
				$errors[]=$error->getMessage();
				$failed=true;
			}

		if(php_sapi_name() === 'cli')
		{
			foreach($log as $file)
				echo 'Removed '.$file.PHP_EOL;

			foreach($errors as $error)
				echo 'Error: '.$error.PHP_EOL;

			if($failed)
				exit(1);

			exit();
		}

		return [$log, $errors];
	})([
		// edit this array
		'app_session_mod_files'=>[
			function($file) use(&$log){
				$log[]=$file;
			}
		]
	]);
?>