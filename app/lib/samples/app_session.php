<?php
	/*
	 * Implements APP_NO_SESSION_IN_COOKIE switch
	 * and APP_SESSION_COOKIE_KEY key
	 * for app_session_mod_cookie
	 *
	 * Note:
	 *  throws an app_exception if session cannot be started
	 *
	 * See:
	 *  app/src/routes/samples/database-test.php
	 *  app/src/routes/samples/login-component-test.php
	 *  app/src/routes/samples/login-library-test.php
	 */

	function app_session()
	{
		if(!class_exists('app_session_backend'))
			require APP_LIB.'/app_session.php';

		if(app_env('APP_NO_SESSION_IN_COOKIE') !== 'true')
			app_session::add(new app_session_mod_cookie(
				app_env('APP_SESSION_COOKIE_KEY', null)
			));

		if(!
			app_session
			::	add(new app_session_mod_files())
			::	session_start()
		)
			throw new app_exception(
				'Session cannot be started'
			);
	}
?>