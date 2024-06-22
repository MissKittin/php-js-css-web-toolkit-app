<?php
	class model_name
	{
		private $pdo_handler;
		//private $query_builder;
		//private $orm;

		public function __construct($params)
		{
			$this->pdo_handler=pdo_instance();

			//$this->query_builder=new pdo_crud_builder([
			//	'pdo_handler'=>$this->pdo_handler
			//]);
			//$this->query_builder->set_fetch_mode(PDO::FETCH_NAMED);

			//$this->orm=new pdo_cheat([
			//	'pdo_handler'=>$this->pdo_handler,
			//	'table_name'=>$params['table_name']
			//]);

			//
		}

		//
	}
?>