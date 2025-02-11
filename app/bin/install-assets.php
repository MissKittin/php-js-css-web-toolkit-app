<?php
	/*
	 * Assets installer for example application
	 *
	 * Warning:
	 *  copy_recursive.php library is required
	 */

	if(!isset($argv[1]))
	{
		echo 'Usage:'.PHP_EOL;
		echo ' '.basename(__FILE__).' cp|ln|mklink'.PHP_EOL;
		echo PHP_EOL;
		echo 'Where:'.PHP_EOL;
		echo ' cp -> copy files'.PHP_EOL;
		echo ' ln -> create symlink (*nix only)'.PHP_EOL;
		echo ' mklink -> use windows mklink utility'.PHP_EOL;
		exit(1);
	}

	if(!defined('APP_STDLIB'))
		require __DIR__.'/../lib/stdlib.php';

	echo ' -> Including copy_recursive.php';
		if(@(include TK_LIB.'/copy_recursive.php') === false)
		{
			echo ' [FAIL]'.PHP_EOL;
			exit(1);
		}
	echo ' [ OK ]'.PHP_EOL;

	chdir(APP_DIR);
	@mkdir('./assets');

	$fc=[
		[ // files_cp
			'./com', // app
			'../tk/com',
			'../tk/lib'
		],
		[ // files_nix
			'../com', // app
			'../../tk/com',
			'../../tk/lib'
		],
		[ // files_win
			'..\\com', // app
			'..\\..\\tk\\com',
			'..\\..\\tk\\lib'
		]
	];
	$files_cp=[
		$fc[0][0].'/basic_template/assets/basic-template.js',
		$fc[0][0].'/basic_template/assets/basic-template.css',
		$fc[0][2].'/sendNotification.js',
		$fc[0][2].'/multipage.js',
		$fc[0][1].'/login/templates/default/assets/login_default_bright.css',
		$fc[0][1].'/login/templates/default/assets/login_default_dark.css',
		$fc[0][1].'/login/templates/materialized/assets/login_materialized.css',
		$fc[0][1].'/middleware_form/templates/default/assets/middleware_form_default_bright.css',
		$fc[0][1].'/middleware_form/templates/default/assets/middleware_form_default_dark.css',
		$fc[0][1].'/middleware_form/templates/materialized/assets/middleware_form_materialized.css',
		$fc[0][2].'/simpleblog_materialized.css'
	];
	$files_nix=[
		$fc[1][0].'/basic_template/assets/basic-template.js',
		$fc[1][0].'/basic_template/assets/basic-template.css',
		$fc[1][2].'/sendNotification.js',
		$fc[1][2].'/multipage.js',
		$fc[1][1].'/login/templates/default/assets/login_default_bright.css',
		$fc[1][1].'/login/templates/default/assets/login_default_dark.css',
		$fc[1][1].'/login/templates/materialized/assets/login_materialized.css',
		$fc[1][1].'/middleware_form/templates/default/assets/middleware_form_default_bright.css',
		$fc[1][1].'/middleware_form/templates/default/assets/middleware_form_default_dark.css',
		$fc[1][1].'/middleware_form/templates/materialized/assets/middleware_form_materialized.css',
		$fc[1][2].'/simpleblog_materialized.css'
	];
	$files_win=[
		$fc[2][0].'\\basic_template\\assets\\basic-template.js',
		$fc[2][0].'\\basic_template\\assets\\basic-template.css',
		$fc[2][2].'\\sendNotification.js',
		$fc[2][2].'\\multipage.js',
		$fc[2][1].'\\login\\templates\\default\\assets\\login_default_bright.css',
		$fc[2][1].'\\login\\templates\\default\\assets\\login_default_dark.css',
		$fc[2][1].'\\login\\templates\\materialized\\assets\\login_materialized.css',
		$fc[2][1].'\\middleware_form\\templates\\default\\assets\\middleware_form_default_bright.css',
		$fc[2][1].'\\middleware_form\\templates\\default\\assets\\middleware_form_default_dark.css',
		$fc[2][1].'\\middleware_form\\templates\\materialized\\assets\\middleware_form_materialized.css',
		$fc[2][2].'\\simpleblog_materialized.css'
	];

	switch($argv[1])
	{
		case 'cp':
			foreach($files_cp as $target)
			{
				echo $target.' => '.basename($target).PHP_EOL;
				copy_recursive($target, './assets/'.basename($target));
			}
		break;
		case 'ln':
			@symlink(basename(__FILE__), './bin/install-assets-test');

			if(@readlink('./bin/install-assets-test') === false)
			{
				echo 'Cannot create symlink'.PHP_EOL;
				@unlink('./bin/install-assets-test');
				exit(1);
			}

			unlink('./bin/install-assets-test');

			foreach($files_nix as $target)
			{
				echo $target.' => '.basename($target).PHP_EOL;
				symlink($target, './assets/'.basename($target));
			}
		break;
		case 'mklink':
			if(strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN')
			{
				echo 'This is not a MS Windows'.PHP_EOL;
				exit(1);
			}

			if(@exec('mklink bin\\install-assets-test '.basename(__FILE__)) !== 'symbolic link created for bin\install-assets-test <<===>> install-assets.php')
			{
				echo 'mklink failed'.PHP_EOL;
				@unlink('bin\\install-assets-test');
				exit(1);
			}

			unlink('bin\\install-assets-test');

			foreach($files_win as $target)
			{
				echo $target.' => '.basename($target).PHP_EOL;

				$dir_switch='';

				if(is_dir(__DIR__.'\\'.$target))
					$dir_switch='/D';

				exec('mklink '.$dir_switch.' .\\assets\\'.basename($target).' '.$target);
			}
		break;
		default:
			echo 'Invaild argument'.PHP_EOL;
			echo PHP_EOL;
			echo 'Usage:'.PHP_EOL;
			echo ' '.basename(__FILE__).' cp|ln|mklink'.PHP_EOL;
			echo PHP_EOL;
			echo 'Where:'.PHP_EOL;
			echo ' cp -> copy files'.PHP_EOL;
			echo ' ln -> create symlink (*nix only)'.PHP_EOL;
			echo ' mklink -> use mklink utility (windows only)'.PHP_EOL;
			exit(1);
	}
?>