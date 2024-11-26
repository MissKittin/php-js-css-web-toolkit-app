<?php
	// Create a new git repository with the application skeleton

	$git_bin='git';
	$result_code=null;

	if(isset($argv[1]))
		$git_bin=$argv[1];

	passthru(
		'"'.$git_bin.'" --version',
		$result_code
	);

	if($result_code !== 0)
	{
		echo 'Error: git not found'.PHP_EOL
		.	PHP_EOL
		.	'Use: '.$argv[0].' path/to/git'.PHP_EOL;

		exit(1);
	}

	if(!defined('APP_STDLIB'))
		require __DIR__.'/../lib/stdlib.php';

	chdir(APP_ROOT);

	echo '-> Removing sample application'.PHP_EOL;
		system('"'.PHP_BINARY.'" "'.__DIR__.'/remove-samples.php"');

	echo '-> Setting up git repository'.PHP_EOL;
		system('"'.$git_bin.'" submodule set-url tk '.shell_exec('"'.$git_bin.'" -C tk config --get remote.origin.url'));
		system('"'.$git_bin.'" remote remove origin');
		system('"'.$git_bin.'" checkout --orphan _git_init_new_master_');
		system('"'.$git_bin.'" branch -D master');
		system('"'.$git_bin.'" branch -m master');

	echo PHP_EOL.'Now set the repository address:'.PHP_EOL
	.	' git remote add origin "https://my-server.git/my-app.git"'.PHP_EOL
	.	'add files to the index:'.PHP_EOL
	.	' git add -A'.PHP_EOL
	.	'create first commit:'.PHP_EOL
	.	' git commit -a -m "Initial commit"'.PHP_EOL
	.	'and push master branch:'.PHP_EOL
	.	' git push origin master'.PHP_EOL;

	unlink(__FILE__);
?>