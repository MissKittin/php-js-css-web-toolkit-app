<?php
	//namespace app\src\models;

	class session_model_template
	{
		protected $model_params;
		protected $session_name;

		public function __construct($model_params)
		{
			$this->model_params=$model_params;
			$this->session_name=$model_params['session_name'];

			//
		}

		protected function isset($key=null)
		{
			if(session_status() !== PHP_SESSION_ACTIVE)
				throw new app_exception('Session closed or not started');

			if($key === null)
				return isset(
					$_SESSION[$this->session_name]
				);

			return isset(
				$_SESSION[$this->session_name][$key]
			);
		}
		protected function get($key=null, $default_value=null)
		{
			if($key === null)
			{
				if($this->isset())
					return $_SESSION[$this->session_name];

				return $default_value;
			}

			if($this->isset($key))
				return $_SESSION[$this->session_name][$key];

			return $default_value;
		}
		protected function set($key, $value)
		{
			if(session_status() !== PHP_SESSION_ACTIVE)
				throw new app_exception('Session closed or not started');

			if($key === null)
			{
				$_SESSION[$this->session_name]=$value;
				return $this;
			}

			$_SESSION[$this->session_name][$key]=$value;

			return $this;
		}
		protected function unset($key)
		{
			if(session_status() !== PHP_SESSION_ACTIVE)
				throw new app_exception('Session closed or not started');

			if($key === null)
			{
				unset(
					$_SESSION[$this->session_name]
				);

				return $this;
			}

			unset(
				$_SESSION[$this->session_name][$key]
			);

			return $this;
		}

		//
	}
?>