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

			//static::$response->response_content(
			//	$view->view('view_template'/*, 'page_content.html'*/)
			//)->send_response();
			//app_template::finish_request();

			return $view;
		}
		public static function init_request_response()
		{
			if(!class_exists('http_request'))
				require TK_LIB.'/http_request_response.php';

			static::$request=new http_request();
			static::$response=new http_response();

			http_response
			::	middleware_arg('request', static::$request)
			::	middleware_arg('response', static::$response)
			::	middleware_arg('session', null)
			::	middleware_arg('files', null);

			if(session_status() === PHP_SESSION_ACTIVE)
			{
				static::$session=new http_session(
					//static::model_params()['session_name'] // subkey
				);

				http_response::middleware_arg(
					'session',
					static::$session
				);
			}

			if(static::$request->method() === 'POST')
			{
				static::$files=new http_files();

				http_response::middleware_arg(
					'files',
					static::$files
				);
			}

			//http_response::middleware(function($request, $response, $session, $files){
			//	//before
			//}, true);
			//http_response::middleware(function($request, $response, $session, $files){
			//	//after
			//});
		}

		public static function model_params()
		{
			return [
				'table_name'=>'', // pdo_model_template.php
				'file_name'=>'', // json_model_template.php
				'session_name'=>'' // session_model_template.php
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