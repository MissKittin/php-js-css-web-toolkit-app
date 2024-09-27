<?php
	/*
	 * Application builder
	 *
	 * Warning:
	 *  install-assets.php app tool is required
	 *  replace-public-index-with-link.php app tool is required
	 *  assets-compiler.php tool is required
	 *  composer.phar or get-composer.php tool is required
	 *  matthiasmullie-minify.php tool is required
	 */

	if(file_exists(__DIR__.'/replace-public-index-with-link.php'))
		system('"'.PHP_BINARY.'" '
		.	'"'.__DIR__.'/replace-public-index-with-link.php"'
		);

	if(strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')
		system('"'.PHP_BINARY.'" '
		.	'"'.__DIR__.'/install-assets.php" '
		.	'cp'
		);
	else
		system('"'.PHP_BINARY.'" '
		.	'"'.__DIR__.'/install-assets.php" '
		.	'ln'
		);

	system('"'.PHP_BINARY.'" '
	.	'"'.__DIR__.'/../../tk/bin/assets-compiler.php" '
	.	'"'.__DIR__.'/../assets" '
	.	'"'.__DIR__.'/../../public/assets"'
	);

	if(!file_exists(__DIR__.'/../../tk/bin/composer.phar'))
		system('"'.PHP_BINARY.'" '
		.	'"'.__DIR__.'/../../tk/bin/get-composer.php"'
		);

	if(!file_exists(__DIR__.'/../../tk/bin/composer'))
	{
		mkdir(__DIR__.'/../../tk/bin/composer');
		system('"'.PHP_BINARY.'" '
		.	'"'.__DIR__.'/../../tk/bin/composer.phar" '
		.	'--optimize-autoloader '
		.	'--no-cache '
		.	'"--working-dir='.__DIR__.'/../../tk/bin/composer" '
		.	'require matthiasmullie/minify'
		);
	}

	system('"'.PHP_BINARY.'" '
	.	'"'.__DIR__.'/../../tk/bin/matthiasmullie-minify.php" '
	.	'--dir "'.__DIR__.'/../../public/assets"'
	);
?>