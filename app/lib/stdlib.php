<?php
	/*
	 * Application standard library
	 * used by entrypoint, database configurations and tools
	 */

	const APP_STDLIB=1;

	const APP_DIR=__DIR__.'/..';
	const APP_CTRL=APP_DIR.'/controllers';
	const APP_DB=APP_DIR.'/databases';
	const APP_LIB=__DIR__;
	const APP_MOD=APP_DIR.'/models';
	const APP_ROOT=APP_DIR.'/..';
	const APP_ROUT=APP_DIR.'/routes';
	const APP_TEMPL=APP_DIR.'/templates';
	const APP_VIEW=APP_DIR.'/views';

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
					throw new Exception('APP STDLIB: mkdir '.$directory.' failed');
		})();
?>