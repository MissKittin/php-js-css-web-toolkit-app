<?php
	/*
	 * Application standard library
	 * used by other libraries, entrypoint, database configurations and tools
	 */

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

	if(file_exists(APP_ROOT.'/tk.phar'))
	{
		define('TK_PHAR', APP_ROOT.'/tk.phar');
		define('TK_COM', TK_PHAR.'/com');
		define('TK_LIB', TK_PHAR.'/lib');
	}
	else
	{
		define('TK_COM', APP_ROOT.'/tk/com');
		define('TK_LIB', APP_ROOT.'/tk/lib');
	}

	if(is_dir(APP_ROOT.'/tke'))
	{
		if(file_exists(APP_ROOT.'/tke.phar'))
		{
			define('TKE_PHAR', APP_ROOT.'/tke.phar');
			define('TKE_LIB', TKE_PHAR.'/lib');
		}
		else
			define('TKE_LIB', APP_ROOT.'/tke');
	}

	class app_exception extends Exception {}
	class app_env
	{
		protected static $registry=null;

		public static function getenv(
			string $variable,
			$default_value=false
		){
			if(static::$registry === null)
			{
				if(!class_exists('dotenv'))
					require TK_LIB.'/dotenv.php';

				static::$registry=new dotenv(APP_ROOT.'/.env');
			}

			return static::$registry->getenv($variable, $default_value);
		}
	}

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
					throw new app_exception('APP STDLIB: mkdir '.$directory.' failed');
		})();
?>