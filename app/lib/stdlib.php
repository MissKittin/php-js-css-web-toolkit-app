<?php
	/*
	 * Application standard library
	 * used by other libraries, entrypoint, database configurations and tools
	 *
	 * Warning:
	 *  if you want to change the toolkit directory
	 *   to local (./com, ./lib), phar (./tk.phar, ./tke.phar) or composer (./vendor)
	 *   you need to remove the var/cache/stdlib_cache.php file
	 *  dotenv.php library is required
	 */

	// constants
		const APP_STDLIB=1;

		const APP_DIR=__DIR__.'/..';
		const APP_ROOT=APP_DIR.'/..';

		const APP_COM=APP_DIR.'/com';
		const APP_LIB=__DIR__;

		const APP_SRC=APP_DIR.'/src';
		const APP_CTRL=APP_SRC.'/controllers';
		const APP_DB=APP_SRC.'/databases';
		const APP_MODEL=APP_SRC.'/models';
		const APP_ROUTE=APP_SRC.'/routes';
		const APP_VIEW=APP_SRC.'/views';

		const VAR_DIR=APP_ROOT.'/var';
		const VAR_CACHE=VAR_DIR.'/cache';
		const VAR_LIB=VAR_DIR.'/lib';
		const VAR_DB=VAR_LIB.'/databases';
		const VAR_LOG=VAR_DIR.'/log';
		const VAR_RUN=VAR_DIR.'/run';
		const VAR_SESS=VAR_LIB.'/sessions';
		const VAR_TMP=VAR_DIR.'/tmp';

		if(
			file_exists(VAR_CACHE.'/stdlib_cache.php') &&
			(require VAR_CACHE.'/stdlib_cache.php')
		)
			unlink(VAR_CACHE.'/stdlib_cache.php');

		if(!defined('APP_STDLIB_CACHE'))
		{
			if(is_file(APP_ROOT.'/tk.phar'))
			{
				define('TK_PHAR', APP_ROOT.'/tk.phar');
				define('TK_COM', TK_PHAR.'/com');
				define('TK_LIB', TK_PHAR.'/lib');
			}
			else if(is_dir(APP_ROOT.'/tk'))
			{
				define('TK_COM', APP_ROOT.'/tk/com');
				define('TK_LIB', APP_ROOT.'/tk/lib');
			}
			else
			{
				if(is_dir(APP_ROOT.'/com'))
				{
					define('TK_COM', APP_ROOT.'/com');
					define('TKE_COM', APP_ROOT.'/com');
				}
				else if(is_dir(APP_ROOT.'/vendor/misskittin/php-js-css-web-toolkit/com'))
					define(
						'TK_COM',
						APP_ROOT.'/vendor/misskittin/php-js-css-web-toolkit/com'
					);

				if(is_dir(APP_ROOT.'/lib'))
				{
					define('TK_LIB', APP_ROOT.'/lib');
					define('TKE_LIB', APP_ROOT.'/lib');
				}
				else if(is_dir(APP_ROOT.'/vendor/misskittin/php-js-css-web-toolkit/lib'))
					define(
						'TK_COM',
						APP_ROOT.'/vendor/misskittin/php-js-css-web-toolkit/lib'
					);
			}

			if(
				(!defined('TKE_COM')) &&
				(!defined('TKE_LIB'))
			){
				if(is_file(APP_ROOT.'/tke.phar')){
					define('TKE_PHAR', APP_ROOT.'/tke.phar');
					define('TKE_COM', TKE_PHAR.'/com');
					define('TKE_LIB', TKE_PHAR.'/lib');
				}
				else if(is_dir(APP_ROOT.'/tke'))
				{
					define('TKE_COM', APP_ROOT.'/tke/com');
					define('TKE_LIB', APP_ROOT.'/tke/lib');
				}
				else if(is_dir(
					APP_ROOT.'/vendor/misskittin/php-js-css-web-toolkit-extras'
				)){
					define(
						'TKE_COM',
						APP_ROOT.'/vendor/misskittin/php-js-css-web-toolkit-extras/com'
					);

					define(
						'TKE_LIB',
						APP_ROOT.'/vendor/misskittin/php-js-css-web-toolkit-extras/lib'
					);
				}
			}
		}

	// functions
		function app_env(
			string $variable,
			$default_value=false
		){
			static $registry=null;

			if($registry === null)
			{
				if(!class_exists('dotenv'))
					require TK_LIB.'/dotenv.php';

				$registry=new dotenv(APP_ROOT.'/.env');
			}

			return $registry->getenv($variable, $default_value);
		}

	// classes
		class app_exception extends Exception {}

	// bootstrap
		if(!file_exists(VAR_DIR))
			(function(){
				foreach([
					VAR_DIR,
					VAR_CACHE,
					VAR_LIB,
					VAR_DB,
					VAR_LOG,
					VAR_RUN,
					VAR_TMP
				] as $directory)
					if(!mkdir($directory))
						throw new app_exception(
							'APP STDLIB: mkdir '.$directory.' failed'
						);
			})();

		if(!file_exists(VAR_CACHE.'/stdlib_cache.php'))
		{
			file_put_contents(VAR_CACHE.'/stdlib_cache.php', '<?php '
			.	"if(APP_ROOT !== '".APP_ROOT."')"
			.		'return true;'
			);

			if(defined('TK_PHAR'))
				file_put_contents(VAR_CACHE.'/stdlib_cache.php', ''
				.	"const TK_PHAR='".TK_PHAR."';"
				,	FILE_APPEND
				);

			if(defined('TKE_PHAR'))
				file_put_contents(VAR_CACHE.'/stdlib_cache.php', ''
				.	"const TKE_PHAR='".TKE_PHAR."';"
				,	FILE_APPEND
				);

			if(defined('TKE_COM'))
				file_put_contents(VAR_CACHE.'/stdlib_cache.php', ''
				.	"const TKE_COM='".TKE_COM."';"
				.	"const TKE_LIB='".TKE_LIB."';"
				,	FILE_APPEND
				);

			file_put_contents(VAR_CACHE.'/stdlib_cache.php', ''
			.	"const TK_COM='".TK_COM."';"
			.	"const TK_LIB='".TK_LIB."';"
			.	'const APP_STDLIB_CACHE=1;'
			.	'return false;'
			.	' ?>'
			,	FILE_APPEND
			);
		}
?>