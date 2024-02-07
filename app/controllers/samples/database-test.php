<?php
	class database_test_controller
	{
		public static function main($model, $view)
		{
			require TK_LIB.'/check_var.php';
			require TK_LIB.'/sec_csrf.php';

			$view['db_cars']=$model;

			if(csrf_check_token('post'))
				switch(self::if2switch($_POST, ['create', 'read', 'update', 'delete']))
				{
					case 'create':
						$model->create([
							$_POST['car_name'],
							$_POST['car_price']
						]);
					break;
					case 'read':
						$view['do_read']=true;
					break;
					case 'update':
						$model->update(
							$_POST['car_id'],
							[
								$_POST['car_name'],
								$_POST['car_price']
							]
						);
					break;
					case 'delete':
						if(
							($_POST['car_id'] === '') &&
							(check_post('delete_allow_db_flush') === 'allow')
						)
							$model->delete();
						else
							$model->delete($_POST['car_id']);
					break;
				}

			return $view;
		}
		public static function model_params()
		{
			return [
				'cars',
				'id',
				'name,price'
			];
		}

		private static function if2switch($source_array, $param_array)
		{
			foreach($param_array as $param)
				if(isset($source_array[$param]))
					return $param;
		}
	}
?>