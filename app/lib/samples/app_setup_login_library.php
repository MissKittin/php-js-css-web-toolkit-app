<?php
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
		setup_login_library(app_env('APP_PASSWD_HASH'));
	}
?>