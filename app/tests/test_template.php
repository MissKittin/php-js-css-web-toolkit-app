<?php
	/*
	 * Test description
	 *
	 * Warning:
	 *  stdlib.php app library is required
	 */

	$stdlib_path=__DIR__.'/../../lib/stdlib.php';

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

	// toolkit libraries
	foreach([
		// 'toolkit_library_name.php'
	] as $library){
		echo ' -> Including '.$library;
			if(is_file(TK_LIB.'/'.$library))
			{
				if(@(include TK_LIB.'/'.$library) === false)
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
	}

	// toolkit components
	foreach([
		// 'component_name'
	] as $library){
		echo ' -> Including '.$library;
			if(is_file(TK_COM.'/'.$library.'/main.php'))
			{
				if(@(include TK_COM.'/'.$library.'/main.php') === false)
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
	}

	// toolkit extras libraries
	foreach([
		// 'toolkit_library_name.php'
	] as $library){
		echo ' -> Including '.$library;
			if(is_file(TKE_LIB.'/'.$library))
			{
				if(@(include TKE_LIB.'/'.$library) === false)
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
	}

	// app libraries
	foreach([
		// 'app_library_name.php'
	] as $library){
		echo ' -> Including '.$library;
			if(is_file(APP_LIB.'/'.$library))
			{
				if(@(include APP_LIB.'/'.$library) === false)
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
	}

	// app components
	foreach([
		// 'component_name'
	] as $library){
		echo ' -> Including '.$library;
			if(is_file(APP_COM.'/'.$library))
			{
				if(@(include APP_COM.'/'.$library) === false)
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
	}

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
	echo ' -> Testing something';
		if(true)
			echo ' [ OK ]'.PHP_EOL;
		else
		{
			echo ' [FAIL]'.PHP_EOL;
			$failed=true;
		}

	if($failed)
		exit(1);
?>