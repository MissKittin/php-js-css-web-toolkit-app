<?php
	class login_component_test_credentials
	{
		private static $credentials_file=VAR_LIB.'/login_component_test_credentials/login_component_test_new_password';
		private static $password_hash_cache=null;

		public static function add_login_validator_rules()
		{
			login_validator::on_login(function($login, $password){
				if(login_password_needs_rehash(self::$password_hash_cache))
					self::save_new_password($password);

				self::$password_hash_cache=null;

				return true;
			});
		}
		public static function change_password_requested()
		{
			return (!file_exists(self::$credentials_file));
		}
		public static function read_password($login)
		{
			if($login !== 'test')
				return [null, null];

			$password_hash=string2hash('test');

			// import new password
			if(file_exists(self::$credentials_file))
				$password_hash=file_get_contents(self::$credentials_file);

			self::$password_hash_cache=$password_hash;

			return ['test', $password_hash];
		}
		public static function save_new_password($password)
		{
			if(!file_exists(dirname(self::$credentials_file)))
				mkdir(dirname(self::$credentials_file));

			file_put_contents(
				self::$credentials_file,
				string2hash($password)
			);
		}
	}
	class login_component_test_session_manager
	{
		/*
		 * A simple implementation of the session registry
		 * that allows you to log out the user from specific browser(s)
		 * e.g. from the app administration panel
		 *
		 * Note:
		 *  delete $database_file to invalidate all sessions
		 *
		 * Warning:
		 *  for demonstration purposes only
		 *  if you want to implement such a solution,
		 *   do it on a relational database
		 */

		private static $database_file=VAR_LIB.'/login_component_test_credentials/login_component_test_session_manager.json';

		private static function lock_unlock_file($make_lock)
		{
			if($make_lock)
			{
				$max_wait=500; // 5 seconds

				while(file_exists(self::$database_file.'.lock'))
				{
					usleep(10000);

					if($max_wait-- === 0)
						throw new app_exception('Lock file still exists');
				}

				if(file_put_contents(self::$database_file.'.lock', '') === false)
					throw new app_exception('Unable to create the lock file');

				return;
			}

			if(!file_exists(self::$database_file.'.lock'))
				throw new app_exception('Lock file not exists - cache not saved');

			unlink(self::$database_file.'.lock');
		}

		private static function add_token()
		{
			if(!function_exists('rand_str_secure'))
				require TK_LIB.'/rand_str.php';

			$token=rand_str_secure(50);
			$_SESSION['_session_manager_token']=$token;

			$database=[];

			if(!file_exists(dirname(self::$database_file)))
				mkdir(dirname(self::$database_file));

			self::lock_unlock_file(true);

			if(file_exists(self::$database_file))
				$database=json_decode(
					file_get_contents(self::$database_file),
					true
				);

			$database[]=$token;

			if(file_put_contents(
				self::$database_file,
				json_encode($database)
			) === false){
				self::lock_unlock_file(false);
				return false;
			}

			self::lock_unlock_file(false);

			return true;
		}
		private static function check_token()
		{
			if(!isset($_SESSION['_session_manager_token']))
				return false;

			if(!file_exists(self::$database_file))
				return false;

			return in_array(
				$_SESSION['_session_manager_token'],
				json_decode(
					file_get_contents(self::$database_file),
					true
				)
			);
		}
		private static function delete_token()
		{
			if(!isset($_SESSION['_session_manager_token']))
				return;

			if(!file_exists(self::$database_file))
				return;

			self::lock_unlock_file(true);

			$database=json_decode(
				file_get_contents(self::$database_file),
				true
			);

			foreach($database as $index=>$token)
				if($token === $_SESSION['_session_manager_token'])
				{
					unset($database[$index]);
					break;
				}

			file_put_contents(
				self::$database_file,
				json_encode($database)
			);

			self::lock_unlock_file(false);
		}

		public static function add_login_validator_rules()
		{
			login_validator
			::	on_login(function(){
					return self::add_token();
				})
			::	on_logout(function(){
					self::delete_token();
				})
			::	add(function(){
					return self::check_token();
				});
		}
	}
?>