<?php
	//namespace app\src\controllers;

	//use http_request;
	//use http_session;
	//use http_files;
	//use http_response;

	class controller_template
	{
		protected static $model;
		protected static $cache;
		protected static $view;
		protected static $request;
		protected static $session;
		protected static $files;
		protected static $response;

		public static function main($model, $view, $cache=null)
		{
			static::$model=$model;
			static::$cache=$cache;
			static::$view=$view;

			//static::init_request_response();

			//

			return $view;
		}
		public static function init_request_response()
		{
			if(!class_exists('http_request'))
				require TK_LIB.'/http_request_response.php';

			self::$request=new http_request();
			self::$session=new http_session();
			self::$files=new http_files();
			self::$response=new http_response();
		}

		public static function model_params()
		{
			return [
				'table_name'=>''
			];
		}
		public static function cache_params()
		{
			return [
				//
			];
		}

		//
	}
?>