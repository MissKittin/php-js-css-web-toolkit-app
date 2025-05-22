<?php
	//namespace app\src\models;

	class json_model_template
	{
		protected static $json_flags=JSON_UNESCAPED_UNICODE;
		protected static $initial_content=[];

		protected $model_params;
		protected $file_name;

		public static function enable_pretty_print()
		{
			static::$json_flags=JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT;
			return static::class;
		}
		public static function set_initial_content($initial_content)
		{
			static::$initial_content=$initial_content;
			return static::class;
		}

		public function __construct($model_params)
		{
			$this->model_params=$model_params;
			$this->file_name=$model_params['file_name'];

			//
		}

		protected function json_get_contents()
		{
			if(!function_exists('json_get_contents'))
				require TK_LIB.'/json_contents.php';

			if(is_file($this->file_name))
				return json_get_contents(
					$this->file_name,
					true
				);

			if(file_exists($this->file_name))
				throw new app_exception(
					$this->file_name.' is not a file'
				);

			$this->json_save_contents(
				static::$initial_content
			);

			return static::$initial_content;
		}
		protected function json_put_contents(/**/)
		{
			$database=$this->json_get_contents();

			//

			$this->json_save_contents($database);
		}
		protected function json_save_contents($database)
		{
			if(!function_exists('json_put_contents'))
				require TK_LIB.'/json_contents.php';

			if(
				file_exists($this->file_name) &&
				(!is_file($this->file_name))
			)
				throw new app_exception(''
				.	$this->file_name.' '
				.	'already exists and is not a file'
				);

			if(json_put_contents(
				$this->file_name,
				$database,
				static::$json_flags
			) === false)
				throw new app_exception(''
				.	__METHOD__.': '
				.	'error while saving database '
				.	'('.$this->file_name.')'
				);
		}

		//
	}
?>