<?php
	/*
	 * Modular session backend
	 *
	 * How it works:
	 *  after executing the app_session::session_start(), the quick_boot() methods are run
	 *  which quickly check whether the environment is ready and start the session
	 *  if they return false, boot() methods are run which prepare the environment
	 *  and then the session is started
	 *
	 * Modules:
	 *  app_session_mod_cookie - store session data in a cookie
	 *  app_session_mod_php - default PHP session handler
	 *  app_session_mod_pdo - store session data in relational database
	 *  app_session_mod_redis - store session data in Redis
	 *  app_session_mod_memcached - store session data in Memcached
	 *  app_session_mod_files - files PHP session handler
	 *   has a method to remove unused sessions
	 *
	 * Usage:
		app_session
		::	add(new app_session_mod_a())
		::	add(new app_session_mod_b())
		::	add(new app_session_mod_c())
		::	session_start();
	 */

	class app_session_exception extends Exception {}

	interface app_session_mod
	{
		public function boot(): bool;
		public function quick_boot(): bool;
	}

	trait app_session_mod_lv_encrypter
	{
		protected $handler_params;
		protected $use_lv_encrypter=false;

		protected function lv_encrypter_construct($handler_params)
		{
			if(!isset($_SERVER['HTTP_HOST']))
				return true;

			if(
				(!extension_loaded('openssl')) ||
				(!extension_loaded('mbstring'))
			)
				return true;

			if($handler_params !== null)
			{
				$this->handler_params=$handler_params;

				if(!isset($this->handler_params['cipher']))
					$this->handler_params['cipher']='aes-256-gcm';
			}

			$this->use_lv_encrypter=true;

			if(!class_exists('lv_cookie_session_handler'))
				require TK_LIB.'/sec_lv_encrypter.php';

			return false;
		}
	}

	abstract class app_session
	{
		// main class - go ahead

		protected static $instances=[];

		public static function add(app_session_mod $instance)
		{
			static::$instances[]=$instance;
			return static::class;
		}
		public static function session_start()
		{
			foreach(static::$instances as $instance)
				if($instance->quick_boot())
				{
					static::$instances=[];
					return true;
				}

			foreach(static::$instances as $instance)
				if($instance->boot())
				{
					static::$instances=[];
					return true;
				}

			return false;
		}
	}

	class app_session_mod_cookie implements app_session_mod
	{
		/*
		 * Checks whether the sec_lv_encrypter.php library can be used
		 * to store session data in a cookie
		 *
		 * Note:
		 *  uses the aes-256-gcm algorithm to encrypt cookies
		 *
		 * Warning:
		 *  openssl extension is required
		 *  mbstring extension is required
		 *  sec_lv_encrypter.php library is required
		 *  app_session_mod_lv_encrypter trait is required
		 *
		 * Required $_SERVER variables:
		 *  HTTP_HOST
		 *
		 * Usage:
			app_session::add(new app_session_mod_cookie());
			app_session::add(new app_session_mod_cookie(
				// use key from env variable (does not save key to file)
				getenv('SESSION_COOKIE_KEY')
			));
		 */

		use app_session_mod_lv_encrypter;

		protected $lv_encrypter_key;
		protected $use_lv_encrypter=false;

		public function __construct($lv_encrypter_key=null)
		{
			if($this->lv_encrypter_construct(null))
				return;

			$this->lv_encrypter_key=$lv_encrypter_key;
		}

		public function boot(): bool
		{
			if(!$this->use_lv_encrypter)
				return false;

			if(!file_exists(VAR_LIB.'/session_start'))
				mkdir(VAR_LIB.'/session_start');

			$lv_encrypter_new_key='';

			if($this->lv_encrypter_key === null)
			{
				$this->lv_encrypter_key=lv_encrypter::generate_key('aes-256-gcm');
				$lv_encrypter_new_key=&$this->lv_encrypter_key;
			}

			file_put_contents(
				VAR_LIB.'/session_start/session_cookie_key',
				$lv_encrypter_new_key
			);

			return $this->quick_boot();
		}
		public function quick_boot(): bool
		{
			if(!$this->use_lv_encrypter)
				return false;

			if(!file_exists(
				VAR_LIB.'/session_start/session_cookie_key'
			))
				return false;

			if($this->lv_encrypter_key === null)
				$this->lv_encrypter_key=file_get_contents(
					VAR_LIB.'/session_start/session_cookie_key'
				);

			lv_cookie_session_handler::register_handler([
				'key'=>$this->lv_encrypter_key,
				'cipher'=>'aes-256-gcm',
				'cookie_domain'=>strtok($_SERVER['HTTP_HOST'], ':')
			]);

			return lv_cookie_session_handler::session_start();
		}
	}
	class app_session_mod_php implements app_session_mod
	{
		/*
		 * Default PHP session handler
		 *
		 * Recommended $_SERVER variables:
		 *  HTTP_HOST
		 *
		 * Usage:
			app_session::add(new app_session_mod_php());
		 */

		protected $session_start_params=[
			'use_cookies'=>1,
			'use_only_cookies'=>1,
			'use_strict_mode'=>1,
			'cookie_httponly'=>1,
			'use_trans_sid'=>0,
			'sid_length'=>'48',
			'sid_bits_per_character'=>'6'
		];

		public function __construct()
		{
			if(isset($_SERVER['HTTP_HOST']))
				$this->session_start_params['cookie_domain']=strtok($_SERVER['HTTP_HOST'], ':');

			if(PHP_VERSION_ID >= 70300)
				$this->session_start_params['cookie_samesite']='Strict';
		}

		public function boot(): bool
		{
			return false;
		}
		public function quick_boot(): bool
		{
			if(!session_start($this->session_start_params))
			{
				// invalid session cookie - remove it

				$_COOKIE['id']='';
				return session_start($this->session_start_params);
			}

			return true;
		}
	}
	class app_session_mod_pdo extends app_session_mod_php
	{
		/*
		 * Checks whether the sec_lv_encrypter.php library can be used
		 * to store session data in relational database
		 * for more info see lv_pdo_session_handler class in the sec_lv_encrypter.php library
		 *
		 * Note:
		 *  uses the aes-256-gcm algorithm to encrypt cookies
		 *
		 * Warning:
		 *  openssl extension is required
		 *  mbstring extension is required
		 *  sec_lv_encrypter.php library is required
		 *  app_session_mod_php class is required
		 *  app_session_mod_lv_encrypter trait is required
		 *
		 * Required $_SERVER variables:
		 *  HTTP_HOST
		 *
		 * Usage:
			app_session::add(new app_session_mod_pdo([
				'key'=>'randomstringforlvencrypter', // required
				'cipher'=>'aes-256-gcm', // optional, default: aes-256-gcm, for lv_encrypter, see lv_encrypter::$supported_ciphers in sec_lv_encrypter.php
				'pdo_handle'=>$pdo_handle, // required
				'table_name'=>'lv_handler_sessions', // optional, default: lv_pdo_session_handler
				'create_table'=>true, // optional, send table creation query, default (safe): true
				'on_error'=>function($message, $pdo_handle) // optional
				{
					my_log_function($message);
				}
			]));
		 */

		use app_session_mod_lv_encrypter;

		protected $use_lv_encrypter=false;

		public function __construct(array $handler_params)
		{
			if($this->lv_encrypter_construct($handler_params))
				return;

			parent::{__FUNCTION__}();
		}

		public function boot(): bool
		{
			return false;
		}
		public function quick_boot(): bool
		{
			if(!$this->use_lv_encrypter)
				return false;

			session_set_save_handler(
				new lv_pdo_session_handler(
					$this->handler_params
				),
				true
			);

			return parent::{__FUNCTION__}();
		}
	}
	class app_session_mod_redis extends app_session_mod_php
	{
		/*
		 * Checks whether the sec_lv_encrypter.php library can be used
		 * to store session data in Redis
		 * for more info see lv_redis_session_handler class in the sec_lv_encrypter.php library
		 *
		 * Note:
		 *  uses the aes-256-gcm algorithm to encrypt cookies
		 *
		 * Warning:
		 *  openssl extension is required
		 *  mbstring extension is required
		 *  sec_lv_encrypter.php library is required
		 *  app_session_mod_php class is required
		 *  app_session_mod_lv_encrypter trait is required
		 *
		 * Required $_SERVER variables:
		 *  HTTP_HOST
		 *
		 * Usage:
			app_session::add(new app_session_mod_pdo([
				'key'=>'randomstringforlvencrypter', // required
				'cipher'=>'aes-256-gcm', // optional, default: aes-256-gcm, for lv_encrypter, see lv_encrypter::$supported_ciphers in sec_lv_encrypter.php
				'redis_handle'=>$redis_handle, // required
				'prefix'=>'lv_session__', // optional, default: lv_redis_session_handler
				'on_error'=>function($message, $redis_handle) // optional
				{
					my_log_function($message);
				}
			]));
		 */

		use app_session_mod_lv_encrypter;

		protected $handler_params;
		protected $use_lv_encrypter=false;

		public function __construct(array $handler_params)
		{
			if($this->lv_encrypter_construct($handler_params))
				return;

			parent::{__FUNCTION__}();
		}

		public function boot(): bool
		{
			return false;
		}
		public function quick_boot(): bool
		{
			session_set_save_handler(
				new lv_redis_session_handler(
					$this->handler_params
				),
				true
			);

			return parent::{__FUNCTION__}();
		}
	}
	class app_session_mod_memcached extends app_session_mod_php
	{
		/*
		 * Checks whether the sec_lv_encrypter.php library can be used
		 * to store session data in Memcached
		 * for more info see lv_memcached_session_handler class in the sec_lv_encrypter.php library
		 *
		 * Note:
		 *  uses the aes-256-gcm algorithm to encrypt cookies
		 *
		 * Warning:
		 *  openssl extension is required
		 *  mbstring extension is required
		 *  sec_lv_encrypter.php library is required
		 *  app_session_mod_php class is required
		 *  app_session_mod_lv_encrypter trait is required
		 *
		 * Required $_SERVER variables:
		 *  HTTP_HOST
		 *
		 * Usage:
			app_session::add(new app_session_mod_pdo([
				'key'=>'randomstringforlvencrypter', // required
				'cipher'=>'aes-256-gcm', // optional, default: aes-256-gcm, for lv_encrypter, see lv_encrypter::$supported_ciphers in sec_lv_encrypter.php
				'memcached_handle'=>$memcached_handle, // required
				'prefix'=>'lv_session__', // optional, default: lv_memcached_session_handler
				'on_error'=>function($message, $memcached_handle) // optional
				{
					my_log_function($message);
				}
			]));
		 */

		use app_session_mod_lv_encrypter;

		protected $handler_params;
		protected $use_lv_encrypter=false;

		public function __construct(array $handler_params)
		{
			if($this->lv_encrypter_construct($handler_params))
				return;

			parent::{__FUNCTION__}();
		}

		public function boot(): bool
		{
			return false;
		}
		public function quick_boot(): bool
		{
			if(!$this->use_lv_encrypter)
				return false;

			session_set_save_handler(
				new lv_memcached_session_handler(
					$this->handler_params
				),
				true
			);

			return parent::{__FUNCTION__}();
		}
	}
	class app_session_mod_files extends app_session_mod_php
	{
		/*
		 * Files PHP session handler
		 *
		 * Recommended $_SERVER variables:
		 *  HTTP_HOST
		 *
		 * Warning:
		 *  app_session_mod_php class is required
		 *
		 * Usage:
			app_session::add(new app_session_mod_files());
		 */

		/*
		 * session_clean() - remove stale sessions
		 *
		 * Note:
		 *  you can execute this from application
		 *   but it is better to run it from cron
		 *  throws an app_session_exception on error
		 *
		 * Usage (without log):
			try {
				app_session_mod_files::session_clean();
			} catch(app_session_exception $error) {}
		 *
		 * Usage (with log):
			try {
				app_session_mod_files::session_clean(function($file){
					// you don't have to define this function
					my_log_function('session-clean:'. $file.' removed');
				});
			} catch(app_session_exception $error) {
				my_log_function('session-clean:'. $error->getMessage());
			}
		 */

		public static function session_clean(?callable $log_callback=null)
		{
			if(!is_dir(VAR_SESS))
				throw new app_session_exception(
					VAR_SESS.' is not a directory'
				);

			if($log_callback === null)
				$log_callback=function(){};

			$gc_maxlifetime=(int)ini_get('session.gc_maxlifetime');

			foreach(
				new directoryIterator(VAR_SESS)
				as $session_file
			){
				if(!$session_file->isFile())
					continue;

				if(substr(
					$session_file->getFilename(),
					0, 5
				) !== 'sess_')
					continue;

				if(time()-$session_file->getMTime() >= $gc_maxlifetime)
				{
					unlink($session_file->getPathname());
					$log_callback($session_file->getFilename());
				}
			}
		}

		public function boot(): bool
		{
			if(!file_exists(VAR_SESS))
				mkdir(VAR_SESS);

			return $this->quick_boot();
		}
		public function quick_boot(): bool
		{
			if(!is_dir(VAR_SESS))
				return false;

			session_save_path(VAR_SESS);
			session_name('id');

			return parent::{__FUNCTION__}();
		}
	}
?>