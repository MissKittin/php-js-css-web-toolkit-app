<?php
	if(!class_exists('pdo_crud_builder'))
		require TK_LIB.'/pdo_crud_builder.php';

	return function($pdo_handle, $mode)
	{
		$crud_builder=new pdo_crud_builder([
			'pdo_handle'=>$pdo_handle
		]);

		try {
			switch($mode)
			{
				case 'apply':
					return $crud_builder->create_table(
						'cars',
						[
							'id'=>'INTEGER NOT NULL AUTO_INCREMENT',
							'name'=>'VARCHAR(255)',
							'price'=>'INTEGER'
						],
						'id'
					)->exec();
				case 'rollback':
					return $crud_builder->drop_table('cars')->exec();
			}
		} catch(PDOException $error) {
			return false;
		}
	};
?>