<?php
	//namespace app\src\models;

	//use pdo_instance;
	//use pdo_crud_builder;
	//use pdo_cheat;
	//use php_debugbar;

	class pdo_model_template
	{
		//protected $database='sqlite';
		protected $database=null; // default

		protected $model_params;
		protected $table_name;
		protected $connected=false;
		protected $pdo_handle=null;
		//protected $query_builder=null;
		//protected $orm=null;

		public function __construct($model_params)
		{
			$this->model_params=$model_params;
			$this->table_name=$model_params['table_name'];

			//
		}

		protected function connect()
		{
			if($this->connected)
				return;

			if(!class_exists('pdo_instance'))
				require APP_LIB.'/pdo_instance.php';

			//if(!class_exists('pdo_crud_builder'))
			//	require TK_LIB.'/pdo_crud_builder.php';

			//if(!class_exists('pdo_cheat'))
			//	require TK_LIB.'/pdo_cheat.php';

			$this->pdo_handle=pdo_instance::get(
				$this->database,
				function($error)
				{
					// $error->getMessage();
				}
			);

			// alternative if you use dependency injection
			//$this->pdo_handle=app_ioc('PDO');

			php_debugbar::get_collector('pdo')->addConnection($this->pdo_handle);

			//$this->query_builder=new pdo_crud_builder([
			//	'pdo_handle'=>$this->pdo_handle
			//]);
			//$this->query_builder->set_fetch_mode(PDO::FETCH_NAMED);

			//$this->orm=new pdo_cheat([
			//	'pdo_handle'=>$this->pdo_handle,
			//	'table_name'=>$this->model_params['table_name']
			//]);

			$this->connected=true;
		}
		protected function disconnect()
		{
			if(!$this->connected)
				return;

			//$this->orm=null;
			//$this->query_builder=$this->query_builder->pdo_disconnect();
			$this->pdo_handle=null;

			pdo_instance::close();

			$this->connected=false;
		}

		//
	}
?>