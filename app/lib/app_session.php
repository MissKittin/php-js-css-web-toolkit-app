<?php
	/*
	 * App session manager
	 *
	 * Functions:
	 *  app_session_start
	 *  app_session_clean
	 */

	class app_session_exception extends Exception {}

	function app_session_start(bool $use_lv_encrypter=true)
	{
		/*
		 * Checks whether the sec_lv_encrypter.php library can be used
		 * to store session data in a cookie
		 * If not, it starts the session with the SessionHandler
		 *
		 * Usage:
			app_session_start();
			app_session_start(false); // do not store session in cookie
		 */

		if(
			$use_lv_encrypter &&
			file_exists(VAR_LIB.'/session_start/session_cookie_key')
		){
			// quick boot up (default)

			if(!class_exists('lv_cookie_session_handler'))
				require TK_LIB.'/sec_lv_encrypter.php';

			lv_cookie_session_handler::register_handler([
				'key'=>file_get_contents(VAR_LIB.'/session_start/session_cookie_key'),
				'cipher'=>'aes-256-gcm'
			]);

			lv_cookie_session_handler::session_start();

			return;
		}

		if(is_dir(VAR_SESS))
		{
			// quick boot up (fallback)

			session_save_path(VAR_SESS);
			session_name('id');
			session_start();

			return;
		}

		if(
			$use_lv_encrypter &&
			extension_loaded('openssl') &&
			extension_loaded('mbstring') &&
			(OPENSSL_VERSION_NUMBER >= 269484159)
		){
			if(!class_exists('lv_cookie_session_handler'))
				require TK_LIB.'/sec_lv_encrypter.php';

			if(!file_exists(VAR_LIB.'/session_start'))
				mkdir(VAR_LIB.'/session_start');

			file_put_contents(VAR_LIB.'/session_start/session_cookie_key', lv_encrypter::generate_key('aes-256-gcm'));

			lv_cookie_session_handler::register_handler([
				'key'=>file_get_contents(VAR_LIB.'/session_start/session_cookie_key'),
				'cipher'=>'aes-256-gcm'
			]);

			lv_cookie_session_handler::session_start();

			return;
		}

		if(!file_exists(VAR_SESS))
			mkdir(VAR_SESS);

		session_save_path(VAR_SESS);
		session_name('id');
		session_start();
	}
	function app_session_clean(?callable $log_callback=null)
	{
		/*
		 * Remove stale sessions
		 *
		 * Note:
		 *  you can execute this from application
		 *   but it is better to run it from cron
		 *  throws an app_session_exception on error
		 *
		 * Usage (without log):
			try {
				app_session_clean();
			} catch(app_session_exception $error) {}
		 *
		 * Usage (with log):
			try {
				app_session_clean(function($file){
					// you don't have to define this function
					my_log_function('session-clean:'. $file.' removed');
				});
			} catch(app_session_exception $error) {
				my_log_function('session-clean:'. $error->getMessage());
			}
		 */

		if($log_callback === null)
			$log_callback=function(){};

		$gc_maxlifetime=(int)ini_get('session.gc_maxlifetime');

		if(is_dir(VAR_SESS))
		{
			foreach(new directoryIterator(VAR_SESS) as $session_file)
			{
				if(!$session_file->isFile())
					continue;

				if(substr($session_file->getFilename(), 0, 5) !== 'sess_')
					continue;

				if(time()-$session_file->getMTime() >= $gc_maxlifetime)
				{
					unlink($session_file->getPathname());
					$log_callback($session_file->getFilename());
				}
			}

			return;
		}

		throw new app_session_exception(VAR_SESS.' is not a directory');
	}
?>