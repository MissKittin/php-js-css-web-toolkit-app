<?php
	function pdo_instance(
		?string $db=null,
		?callable $on_error=null
	){
		/*
		 * pdo_instance configuration via environment variables
		 * also sets the sqlite database as the default
		 *
		 * Environment variables:
		 *  APP_ENV=dev - enable PDO::ERRMODE_EXCEPTION and automatic database seeder
		 *  DB_IGNORE_ENV=true - ignore all variables (default: false)
		 *  DB_TYPE - select database from app/src/databases/samples
		 *
		 * See:
		 *  app/src/controllers/samples/login-component-test.php
		 *  app/src/models/samples/database_test_model.php
		 */

		if(!class_exists('pdo_instance'))
		{
			require APP_LIB.'/pdo_instance.php';

			if(app_env('DB_IGNORE_ENV') === 'true')
				pdo_instance::set_default_db('samples/sqlite');
			else if(app_env('DB_TYPE') !== false)
				pdo_instance::set_default_db('samples/'.app_env('DB_TYPE'));
			else
				pdo_instance::set_default_db('samples/sqlite');

			if(app_env('APP_ENV') === 'dev')
				pdo_instance
				::	enable_exceptions()
				::	enable_seeder();
		}

		return pdo_instance::get($db, $on_error);
	}
?>