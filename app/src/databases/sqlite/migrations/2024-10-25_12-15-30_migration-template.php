<?php
	if(!class_exists('pdo_crud_builder'))
		require TK_LIB.'/pdo_crud_builder.php';

	if(!class_exists('controller_template'))
		require APP_CTRL.'/controller_template.php';

	return function($pdo_handle, $mode)
	{
		$crud_builder=new pdo_crud_builder([
			'pdo_handle'=>$pdo_handle
		]);

		try {
			switch($mode)
			{
				case 'apply':
					if(!$crud_builder->create_table(
						controller_template::model_params()['table_name'],
						[
							'id'=>'INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL',
							'column_a'=>'TYPE'
						]
					)->exec())
						return false;

					return true;
				case 'rollback':
					if(!$crud_builder->drop_table(
						controller_template::model_params()['table_name']
					)->exec())
						return false;

					return true;
			}
		} catch(PDOException $error) {
			return false;
		}
	};
?>