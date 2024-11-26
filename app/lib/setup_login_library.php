<?php
	function setup_login_library($hash)
	{
		/*
		 * Includes sec_login.php library
		 * and sets hashing algorithm
		 *
		 * Note:
		 *  defined password hash options are default for PHP
		 *  change them if you need
		 *
		 * Warning:
		 *  sec_login.php library is required
		 */

		if(!class_exists('login_password_hash'))
			require TK_LIB.'/sec_login.php';

		switch($hash)
		{
			case false:
				// app_env() returned false - environment variable is not defined - use bcrypt
			case 'bcrypt':
				if(!defined('PASSWORD_BCRYPT'))
					throw new app_exception('bcrypt password hash is not supported');

				login_password_hash
				::	algo(PASSWORD_BCRYPT)
				::	options([
						'cost'=>12 // PHP 8.4
					]);
			break;
			case 'argon2i':
				if(!defined('PASSWORD_ARGON2I'))
					throw new app_exception('argon2i password hash is not supported');

				login_password_hash
				::	algo(PASSWORD_ARGON2I)
				::	options([
						'memory_cost'=>PASSWORD_ARGON2_DEFAULT_MEMORY_COST, // int (default: 1024)
						'time_cost'=>PASSWORD_ARGON2_DEFAULT_TIME_COST, // int() (default: 2)
						'threads'=>PASSWORD_ARGON2_DEFAULT_THREADS // int (default: 2)
					]);
			break;
			case 'argon2id':
				if(!defined('PASSWORD_ARGON2ID'))
					throw new app_exception('argon2id password hash is not supported');

				login_password_hash
				::	algo(PASSWORD_ARGON2ID)
				::	options([
						'memory_cost'=>PASSWORD_ARGON2_DEFAULT_MEMORY_COST, // int (default: 1024)
						'time_cost'=>PASSWORD_ARGON2_DEFAULT_TIME_COST, // int() (default: 2)
						'threads'=>PASSWORD_ARGON2_DEFAULT_THREADS // int (default: 2)
					]);
			default:
				throw new app_exception('Unknown password hash algorithm');
		}
	}
?>