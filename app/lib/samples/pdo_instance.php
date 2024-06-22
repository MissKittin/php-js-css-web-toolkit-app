<?php
	/*
	 * pdo_instance configuration via environment variables
	 * also sets the sqlite database as the default
	 *
	 * Environment variables:
	 *  DB_IGNORE_ENV=true - ignore all variables (default: false)
	 *  DB_TYPE - select database from app/src/databases/samples
	 *
	 * See:
	 *  app/src/controllers/samples/login-component-test.php
	 *  app/src/models/samples/database_test_model.php
	 */

	require APP_LIB.'/pdo_instance.php';

	if(getenv('DB_IGNORE_ENV') === 'true')
		pdo_instance(null, null, 'samples/sqlite');
	else if(getenv('DB_TYPE') !== false)
		pdo_instance(null, null, 'samples/'.getenv('DB_TYPE'));
	else
		pdo_instance(null, null, 'samples/sqlite');
?>