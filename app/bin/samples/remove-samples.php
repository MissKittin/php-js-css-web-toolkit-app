<?php
	/*
	 * Remove the sample application
	 *
	 * Warning:
	 *  rmdir_recursive.php library is required
	 */

	if(!defined('APP_STDLIB'))
		require __DIR__.'/../../lib/stdlib.php';

	echo ' -> Including rmdir_recursive.php';
		if(@(include TK_LIB.'/rmdir_recursive.php') === false)
		{
			echo ' [FAIL]'.PHP_EOL;
			exit(1);
		}
	echo ' [ OK ]'.PHP_EOL;

	chdir(__DIR__.'/../..');

	foreach(array_slice(scandir('.'), 2) as $directory)
		if(is_dir('./'.$directory.'/samples'))
		{
			echo ' -> Removing '.$directory.'/samples';
				if(@rmdir_recursive('./'.$directory.'/samples'))
					echo ' [ OK ]'.PHP_EOL;
				else
					echo ' [FAIL]'.PHP_EOL;
		}

	foreach(array_slice(scandir('./src'), 2) as $directory)
		if(is_dir('./src/'.$directory.'/samples'))
		{
			echo ' -> Removing src/'.$directory.'/samples';
				if(@rmdir_recursive('./src/'.$directory.'/samples'))
					echo ' [ OK ]'.PHP_EOL;
				else
					echo ' [FAIL]'.PHP_EOL;
		}

	foreach(array_slice(scandir('./tests'), 2) as $directory)
		if(is_dir('./tests/'.$directory.'/samples'))
		{
			echo ' -> Removing tests/'.$directory.'/samples';
				if(@rmdir_recursive('./tests/'.$directory.'/samples'))
					echo ' [ OK ]'.PHP_EOL;
				else
					echo ' [FAIL]'.PHP_EOL;

			@rmdir('./tests/'.$directory);
		}

	echo ' -> Removing basic-template.css/link_arrow.css';
		if(@unlink('./com/basic_template/assets/basic-template.css/link_arrow.css'))
			echo ' [ OK ]'.PHP_EOL;
		else
			echo ' [FAIL]'.PHP_EOL;

	echo ' -> Editing entrypoint.php';
		$entrypoint=file_get_contents('./entrypoint.php');
		file_put_contents(
			'./entrypoint.php',
			preg_replace_callback(
				'/{((?:[^{}]*|(?R))*)}/x',
				function($match){
					static $switch_php_sapi_name=true; // switch(php_sapi_name())

					// register_shutdown_function
					if(substr(ltrim($match[1]), 0, 10) === '$exec_time')
						return ''
						.	'{'
						.		"\n\t\t".'//'
						.	"\n\t".'}';

					if(strpos($match[0], 'case') !== false)
					{
						if(!$switch_php_sapi_name)
							return ''
							.	'{'
							.		"\n\t\t".'case \'\': require APP_ROUTE.\'/route_template.php\'; break;'
							.		"\n\t\t".'//case \'link\': require APP_ROUTE.\'/link.php\'; break;'
							.		"\n\t\t".'default:'
							.		"\n\t\t\t".'require APP_CTRL.\'/http_error.php\';'
							.		"\n"
							.		"\n\t\t\t".'if(is_dir($_SERVER[\'DOCUMENT_ROOT\'].strtok($_SERVER[\'REQUEST_URI\'], \'?\')))'
							.		"\n\t\t\t".'{'
							.		"\n\t\t\t\t".'http_error(403);'
							.		"\n\t\t\t\t".'break;'
							.		"\n\t\t\t".'}'
							.		"\n"
							.		"\n\t\t\t".'http_error(404);'
							.	"\n\t".'}';

						$switch_php_sapi_name=false;
					}

					// if REQUEST_URI REQUEST_METHOD (conflict with uri_router and superclosure_router)
					//return ''
					//.	'{'
					//.		"\n\t\t".'//'
					//.		"\n\t\t".'exit();'
					//.	"\n\t".'}';

					return $match[0];
				},
				$entrypoint
			)
		);
		$entrypoint=file_get_contents('./entrypoint.php');
		$entrypoint=str_replace('error_log(basename(__FILE__).\' finished\');', '//', $entrypoint);;
		file_put_contents('./entrypoint.php', $entrypoint);
		unset($entrypoint);
	echo ' [ OK ]'.PHP_EOL;

	echo ' -> Removing README.md';
		if(@unlink('./README.md'))
			echo ' [ OK ]';
		else
			echo ' [FAIL]';
		if(@unlink('../README.md'))
			echo ' [ OK ]'.PHP_EOL;
		else
			echo ' [FAIL]'.PHP_EOL;

	echo ' -> Removing HOWTO.md';
		if(@unlink('../HOWTO.md'))
			echo ' [ OK ]'.PHP_EOL;
		else
			echo ' [FAIL]'.PHP_EOL;
?>