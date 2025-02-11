<?php
	/*
	 * Rubber cover for app_db_migrate that writes journals
	 *
	 * See:
	 *  app/src/databases/samples/mysql/seed.php
	 *  app/src/databases/samples/pgsql/seed.php
	 *  app/src/databases/samples/sqlite/seed.php
	 */

	function app_db_migrate_log($params)
	{
		if(!function_exists('app_db_migrate'))
			require APP_LIB.'/app_db_migrate.php';

		if(!function_exists('log_infos'))
			require __DIR__.'/logger.php';

		app_db_migrate(array_merge($params, [
			'callback_on_begin'=>function($migration)
			{
				log_infos('db-migrate')->info('Migration '.$migration.' started');
			},
			'callback_on_skip'=>function($migration)
			{
				log_infos('db-migrate')->info('Migration '.$migration.' skipped');
			},
			'callback_on_error'=>function($migration)
			{
				log_infos('db-migrate')->error('Migration '.$migration.' failed');
				log_fails('db-migrate')->error('Migration '.$migration.' failed');
			},
			'callback_on_error_rollback'=>function($migration)
			{
				log_infos('db-migrate')->info('Migration '.$migration.' rolling back');
			},
			'callback_on_end'=>function($migration, $skipped)
			{
				if($skipped)
				{
					log_infos('db-migrate')->info('Migration '.$migration.' skipped and finished');
					return;
				}

				log_infos('db-migrate')->info('Migration '.$migration.' finished');
			}
		]));
	}
?>