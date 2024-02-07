<?php
	/*
	 * Application standard library
	 * used by entrypoint, database configurations
	 * and tools
	 */

	define('APP_STDLIB', 1);

	define('APP_DIR', __DIR__.'/..');
	define('APP_CTRL', APP_DIR.'/controllers');
	define('APP_DB', APP_DIR.'/databases');
	define('APP_LIB', __DIR__);
	define('APP_MOD', APP_DIR.'/models');
	define('APP_ROOT', APP_DIR.'/..');
	define('APP_ROUT', APP_DIR.'/routes');
	define('APP_TEMPL', APP_DIR.'/templates');
	define('APP_VIEW', APP_DIR.'/views');

	define('VAR_DIR', APP_ROOT.'/var');
	define('VAR_CACHE', VAR_DIR.'/cache');
	define('VAR_LIB', VAR_DIR.'/lib');
	define('VAR_DB', VAR_LIB.'/databases');
	define('VAR_LOG', VAR_DIR.'/log');
	define('VAR_SESS', VAR_LIB.'/sessions');

	define('TK_COM', APP_ROOT.'/tk/com');
	define('TK_LIB', APP_ROOT.'/tk/lib');

	if(!file_exists(VAR_DIR))
	{
		if(!mkdir(VAR_DIR, 0777, true))
			throw new Exception('App stdlib: mkdir '.VAR_DIR.' failed');

		if(!mkdir(VAR_CACHE, 0777, true))
			throw new Exception('App stdlib: mkdir '.VAR_CACHE.' failed');

		if(!mkdir(VAR_LIB, 0777, true))
			throw new Exception('App stdlib: mkdir '.VAR_LIB.' failed');

		if(!mkdir(VAR_DB, 0777, true))
			throw new Exception('App stdlib: mkdir '.VAR_DB.' failed');

		if(!mkdir(VAR_LOG, 0777, true))
			throw new Exception('App stdlib: mkdir '.VAR_LOG.' failed');

		if(!mkdir(VAR_SESS, 0777, true))
			throw new Exception('App stdlib: mkdir '.VAR_SESS.' failed');
	}
?>