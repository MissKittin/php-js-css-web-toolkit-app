<?php
	/*
	 * Controls the $use_lv_encrypter parameter
	 * of the app_session_start function
	 *
	 * See:
	 *  app/src/routes/samples/database-test.php
	 *  app/src/routes/samples/login-component-test.php
	 *  app/src/routes/samples/login-library-test.php
	 */

	function app_session()
	{
		if(!function_exists('app_session_start'))
			require APP_LIB.'/app_session.php';

		if(app_env('APP_NO_SESSION_IN_COOKIE') === 'yes')
			return app_session_start(false);

		app_session_start(true);
	}
?>