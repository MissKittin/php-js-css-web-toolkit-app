<?php
	/*
	 * This part of the application checks whether
	 * the sec_lv_encrypter.php library can be used
	 * to store session data in a cookie
	 * If not, it starts the session with the SessionHandler
	 *
	 * Warning:
	 *  this code was created for demonstration purposes
	 *   and should not be used in production
	 *  $_SESSION_CLEAN_TOOL is reserved for session-clean.php tool
	 *
	 * See:
	 *  bin/session-clean.php
	 *  routes/samples/database-test.php
	 *  routes/samples/login-component-test.php
	 *  routes/samples/login-library-test.php
	 */

	if(isset($_SESSION_CLEAN_TOOL))
		// set vartiable for session-clean.php tool
		$_sessions_dir=VAR_SESS;

	else if(file_exists(VAR_LIB.'/session_cookie_key'))
	{
		// quick boot up (default behavior)

		if(!class_exists('lv_cookie_session_handler'))
			require TK_LIB.'/sec_lv_encrypter.php';

		lv_cookie_session_handler::register_handler([
			'key'=>file_get_contents(VAR_LIB.'/session_cookie_key'),
			'cipher'=>'aes-256-gcm'
		]);

		lv_cookie_session_handler::session_start();
	}
	else if(is_dir(VAR_SESS))
	{
		// quick boot up (fallback)

		session_save_path(VAR_SESS);
		session_name('id');
		session_start();
	}

	else if(
		extension_loaded('openssl') &&
		extension_loaded('mbstring') &&
		(OPENSSL_VERSION_NUMBER >= 269484159)
	){
		if(!class_exists('lv_cookie_session_handler'))
			require TK_LIB.'/sec_lv_encrypter.php';

		file_put_contents(VAR_LIB.'/session_cookie_key', lv_encrypter::generate_key('aes-256-gcm'));

		lv_cookie_session_handler::register_handler([
			'key'=>file_get_contents(VAR_LIB.'/session_cookie_key'),
			'cipher'=>'aes-256-gcm'
		]);

		lv_cookie_session_handler::session_start();
	}
	else
	{
		session_save_path(VAR_SESS);
		session_name('id');
		session_start();
	}
?>