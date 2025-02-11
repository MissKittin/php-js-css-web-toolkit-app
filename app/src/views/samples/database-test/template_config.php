<?php
	php_debugbar::get_template_config($view);

	require APP_LIB.'/basic_template_config.php';
	basic_template_config($view, static::class)

	::	lang('en_US')
	::	title('Database')
	::	meta_description('App test - relational database part')
	::	meta_robots('index,follow')

	::	og('url', (empty($_SERVER['HTTPS']) ? 'http' : 'https').'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'])
	::	og('type', 'website')
	::	og('site_name', 'PHP JS CSS Web Toolkit App')

	::	render_table(function($result){
			if(
				($result === false) ||
				empty($result)
			)
				return false;

			echo '<table><tr><th>ID</th><th>Name</th><th>Price</th></tr>';
				foreach($result as $row)
					echo '<tr><td>'.$row[0].'</td><td>'.$row[1].'</td><td>'.$row[2].'$</td></tr>';
			echo '</table>';

			return true;
		})
	::	print_found_records(function($view){
			echo '<h3>Search results:</h3>';

			if(!$view['render_table'](
				$view['db_cars']->read('name', $_POST['car_name'])
			))
				echo 'No records found';
		})
	::	print_all_records(function($view){
			if(!$view['render_table'](
				$view['db_cars']->read()
			))
				echo 'No records found (maybe the database was not migrated?)';
		})
; ?>