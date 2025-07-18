<?php
	class database_test_model
	{
		/*
		 * Database abstraction class
		 * for elimination of SQL usage
		 *
		 * Warning:
		 *  pdo_crud_bulder.php library is required
		 */

		private $table_name;
		private $table_key;
		private $table_columns;
		private $query_builder=null;

		public function __construct($array)
		{
			$this->table_name=$array[0];
			$this->table_key=$array[1];
			$this->table_columns=$array[2];
		}

		private function connect()
		{
			require APP_LIB.'/samples/pdo_instance.php';

			if(!class_exists('pdo_crud_builder'))
				require TK_LIB.'/pdo_crud_builder.php';

			$this->query_builder=new pdo_crud_builder([
				'pdo_handle'=>pdo_instance()
			]);

			$this->query_builder->set_fetch_mode(PDO::FETCH_NUM);

			php_debugbar::get_collector('pdo')->addConnection(pdo_instance());
		}
		private function disconnect()
		{
			$this->query_builder=$this->query_builder->pdo_disconnect();
			pdo_instance::close();
		}

		public function create($input_array)
		{
			$this->connect();

			return $this->query_builder
			->	insert_into(
					$this->table_name,
					$this->table_columns,
					[$input_array]
				)
			->	exec();
		}
		public function read(
			$column=null,
			$value=null,
			$select='*'
		){
			if($this->query_builder === null)
				$this->connect();

			$this->query_builder
			->	select($select)
			->	from($this->table_name);

			if(
				($column !== null) &&
				($value !== null)
			)
				$this->query_builder->where(
					$column, '=', $value
				);

			$query=$this->query_builder->query();

			if($query !== false)
			{
				// the layout of the database is known

				$query_size=count($query);

				for($i=0; $i<$query_size; ++$i)
					$query[$i]=[
						htmlspecialchars($query[$i][0], ENT_QUOTES, 'UTF-8'),
						htmlspecialchars($query[$i][1], ENT_QUOTES, 'UTF-8'),
						htmlspecialchars($query[$i][2], ENT_QUOTES, 'UTF-8')
					];
			}

			// now close the database connection if this is the last call to the model
			if(
				($column === null) &&
				($value === null)
			)
				$this->disconnect();

			return $query;
		}
		public function update($id, $sql_set)
		{
			$this->connect();

			$table_columns=explode(',', $this->table_columns);

			foreach($sql_set as $set_key=>$set_value)
				$set_array[]=[
					$table_columns[$set_key],
					$set_value
				];

			return $this->query_builder
			->	update($this->table_name)
			->	set($set_array)
			->	where($this->table_key, '=', $id)
			->	exec();
		}
		public function delete($id=null)
		{
			$this->connect();

			$this->query_builder->delete($this->table_name);

			if($id !== null)
				$this->query_builder->where(
					$this->table_key, '=', $id
				);

			return $this->query_builder->exec();
		}
	}
?>