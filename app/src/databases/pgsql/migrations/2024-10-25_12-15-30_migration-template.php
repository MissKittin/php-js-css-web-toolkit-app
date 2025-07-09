<?php
	//use app\src\controllers\controller_template;

	//if(!class_exists('pdo_crud_builder'))
	//	require TK_LIB.'/pdo_crud_builder.php';

	if(!class_exists('pdo_cheat'))
		require TK_LIB.'/pdo_cheat.php';

	if(!class_exists('controller_template'))
		require APP_CTRL.'/controller_template.php';

	return function($pdo_handle, $mode)
	{
		//$crud_builder=new pdo_crud_builder([
		//	'pdo_handle'=>$pdo_handle
		//]);

		$pdo_cheat=new pdo_cheat([
			'pdo_handle'=>$pdo_handle,
			'table_name'=>controller_template::model_params()['table_name']
		]);

		try {
			switch($mode)
			{
				case 'apply':
					//if(!$crud_builder->create_table(
					//	controller_template::model_params()['table_name'],
					//	[
					//		'id'=>'SERIAL PRIMARY KEY',
					//		'column_a'=>'TYPE'
					//	]
					//)->exec())
					//	return false;

					if(!$pdo_cheat->new_table()
					->	id(pdo_cheat::default_id_type)
					->	column_a('TYPE')
					->	save_table())
						return false;

					//

					return true;
				case 'rollback':
					//if(!$crud_builder->drop_table(
					//	controller_template::model_params()['table_name']
					//)->exec())
					//	return false;

					if(!$pdo_cheat->clear_table()->drop_table())
						return false;

					//

					return true;
			}
		} catch(PDOException $error) {
			//

			return false;
		}
	};
?>