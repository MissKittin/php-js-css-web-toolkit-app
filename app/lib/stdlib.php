<?php
	/*
	 * Application standard library
	 * used by entrypoint, database configurations and tools
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

	const TK_COM=APP_ROOT.'/tk/com';
	const TK_LIB=APP_ROOT.'/tk/lib';

	if(is_dir(APP_ROOT.'/tke'))
		define('TKE_LIB', APP_ROOT.'/tke');

	class app_exception extends Exception {}

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