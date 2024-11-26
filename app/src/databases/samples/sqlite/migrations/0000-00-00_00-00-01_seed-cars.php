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
					if(!$crud_builder->insert_into(
						'cars',
						'name,price',
						[
							['Bentley', '52642'],
							['Audo', '5712'],
							['Mercedes', '9000'],
							['Skoda', '29000'],
							['Lolvo', '29000'],
							['Citroen', '41400'],
							['Hummer', '21600']
						]
					)->exec())
						return false;

					return $crud_builder->insert_into(
						'cars',
						'name,price',
						[
							['Single row', '12354']
						]
					)->exec();
				case 'rollback':
					return $crud_builder->delete('cars')->exec();
			}
		} catch(PDOException $error) {
			return false;
		}
	};
?>