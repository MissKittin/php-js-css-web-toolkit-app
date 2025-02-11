<?php
	class app_setup_login_library_exception extends Exception {}
	function app_setup_login_library()
	{
		/*
		 * setup_login_library configuration via environment variable
		 *
		 * See:
		 *  app/src/controllers/routes/login-component-test.php
		 *  app/src/controllers/routes/login-library-test.php
		 */

		require APP_LIB.'/setup_login_library.php';

		if(
			(app_env('APP_PASSWD_HASH') === 'plaintext') &&
			(app_env('APP_ENV') !== 'dev')
		)
			throw new app_setup_login_library_exception(
				'plaintext APP_PASSWD_HASH can only be used in a dev env (export APP_ENV=dev)'
			);

		setup_login_library(app_env('APP_PASSWD_HASH'));
	}
?>