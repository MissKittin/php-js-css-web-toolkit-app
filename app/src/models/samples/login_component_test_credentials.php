<?php
	class login_component_test_credentials
	{
		private static $credentials_file=VAR_LIB.'/login_component_test_credentials/login_component_test_new_password';

		public static function change_password_requested()
		{
			return (!file_exists(self::$credentials_file));
		}
		public static function read_password()
		{
			$password='$2y$10$H2UEollYJTP0l1Qe4njXl.B.2OlJ1/CkhZSIBGn.OLvUGeWNebXPO'; // test

			// import new password
			if(file_exists(static::$credentials_file))
				$password=file_get_contents(static::$credentials_file);

			return ['test', $password];
		}
		public static function save_new_password($new_password)
		{
			if(!file_exists(VAR_LIB.'/login_component_test_credentials'))
				mkdir(VAR_LIB.'/login_component_test_credentials');

			file_put_contents(static::$credentials_file, string2bcrypt($new_password));
		}
	}
?>