<?php
	/*
	 * check-date.php controller test
	 *
	 * Warning:
	 *  stdlib.php app library is required
	 */

	$stdlib_path=__DIR__.'/../../../lib/stdlib.php';

	// standard library
	echo ' -> Including standard library';
		if(is_file($stdlib_path))
		{
			if(@(include $stdlib_path) === false)
			{
				echo ' [FAIL]'.PHP_EOL;
				exit(1);
			}
		}
		else
		{
			echo ' [FAIL]'.PHP_EOL;
			exit(1);
		}
	echo ' [ OK ]'.PHP_EOL;

	// load tested file
	$tested_file=substr(__FILE__, strlen(realpath(APP_DIR.'/tests'))+1);
	echo ' -> Including '.$tested_file;
		if(is_file(APP_SRC.'/'.$tested_file))
		{
			if(@(include APP_SRC.'/'.$tested_file) === false)
			{
				echo ' [FAIL]'.PHP_EOL;
				exit(1);
			}
		}
		else
		{
			echo ' [FAIL]'.PHP_EOL;
			exit(1);
		}
	echo ' [ OK ]'.PHP_EOL;

	$failed=false;

	// test body
	echo ' -> Testing controller';
		if($view['first-question'] === check_date(23,6, 12,8))
			echo ' [ OK ]';
		else
		{
			echo ' [FAIL]';
			$failed=true;
		}
		if($view['second-question'] === check_date(14,9, 23,4))
			echo ' [ OK ]'.PHP_EOL;
		else
		{
			echo ' [FAIL]'.PHP_EOL;
			$failed=true;
		}

	if($failed)
		exit(1);
?>