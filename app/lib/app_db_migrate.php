<?php
	/*
	 * pdo_migrate.php library integration with pdo-connect.php tool
	 * adds a rollback option to the tool
	 *
	 * Warning:
	 *  pdo_migrate.php library is required
	 *  check_var.php library is required in CLI mode
	 *
	 * New pdo-connect.php options:
	 *  --reseed --rollback X
	 *   rollback last X migrations
	 *  --reseed --rollback-all
	 *   be careful with that
	 *
	 * Callbacks:
	 *  usage is the same as for pdo_migrate function
	 *  just add "callback_" prefix e.g:
		$params['callback_on_begin']=function($migration)
		{
			//
		};
	 */

	if(!function_exists('pdo_migrate'))
		require TK_LIB.'/pdo_migrate.php';

	class app_db_migrate_exception extends Exception {}

	if(php_sapi_name() === 'cli')
	{
		function app_db_migrate(array $params)
		{
			foreach([
				'on_begin',
				'on_skip',
				'on_error',
				'on_error_rollback',
				'on_end'
			] as $param)
				if(
					isset($params['callback_'.$param]) &&
					(!is_callable($params['callback_'.$param]))
				)
					throw new app_db_migrate_exception(
						'The input array parameter callback_'.$param.' is not callable'
					);

			if(!function_exists('check_argv_next_param'))
				require TK_LIB.'/check_var.php';

			$rollback_all=check_argv('--rollback-all');
			$rollback=check_argv_next_param('--rollback');

			$params['table_name']='migrations';
			$params['mode']='apply';
			$migration_failed=false;

			$params['on_begin']=function($migration) use($params)
			{
				echo ' -> '.$migration;

				if(isset($params['callback_on_begin']))
					$params['callback_on_begin']($migration);
			};
			$params['on_skip']=function($migration) use($params)
			{
				echo ' [SKIP]'.PHP_EOL;

				if(isset($params['callback_on_skip']))
					$params['callback_on_skip']($migration);
			};
			$params['on_error']=function($migration) use($params, &$migration_failed)
			{
				echo PHP_EOL.' <- '.$migration;

				$migration_failed=true;

				if(isset($params['callback_on_error']))
					$params['callback_on_error']($migration);
			};
			$params['on_error_rollback']=function($migration) use($params)
			{
				echo ' [FAIL]'.PHP_EOL;

				if(isset($params['callback_error_rollback']))
					$params['callback_error_rollback']($migration);
			};
			$params['on_end']=function($migration) use($params, &$migration_failed)
			{
				if(!$migration_failed)
					echo ' [ OK ]'.PHP_EOL;

				if(isset($params['callback_on_end']))
					$params['callback_on_end']($migration);
			};

			if((!$rollback_all) && ($rollback === null))
				return pdo_migrate($params);

			$params['mode']='rollback';

			if($rollback_all)
			{
				echo '<- !!! rolling back all migrations !!!'.PHP_EOL;
				return pdo_migrate($params);
			}

			if(!is_numeric($rollback))
				throw new app_db_migrate_exception('rollback option must be an integer');

			if(!isset($params['directory']))
				throw new app_db_migrate_exception('The directory parameter was not specified');

			if(!is_string($params['directory']))
				throw new app_db_migrate_exception('The input array parameter directory is not a string');

			if(!is_dir($params['directory']))
				throw new app_db_migrate_exception($params['directory'].' is not a directory');

			$rollback=(int)$rollback;
			$migrations=array_reverse(array_diff(
				scandir($params['directory']),
				['.', '..']
			));
			$migrations_len=count($migrations);
			$i=0;

			if($rollback < 1)
				throw new app_db_migrate_exception('rollback option must be greater than 0');

			if($rollback > $migrations_len)
				throw new app_db_migrate_exception('Too many migrations were given to be rolled back - there are '.$migrations_len.' of them');

			if($rollback === $migrations_len)
				echo '<- !!! rolling back all migrations !!!'.PHP_EOL;

			foreach($migrations as $migration)
			{
				if($i++ === $rollback)
					break;

				echo '<- '.pathinfo($migration, PATHINFO_FILENAME).PHP_EOL;

				pdo_migrate($params, $migration);
			}
		}
	}
	else
	{
		function app_db_migrate(array $params)
		{
			$params['table_name']='migrations';
			$params['mode']='apply';

			foreach([
				'on_begin',
				'on_skip',
				'on_error',
				'on_error_rollback',
				'on_end'
			] as $param)
				if(isset($params['callback_'.$param]))
				{
					if(!is_callable($params['callback_'.$param]))
						throw new app_db_migrate_exception(
							'The input array parameter callback_'.$param.' is not callable'
						);

					$params[$param]=function($migration) use($params, $param)
					{
						$params['callback_'.$param]($migration);
					};
				}

			pdo_migrate($params);
		}
	}
?>