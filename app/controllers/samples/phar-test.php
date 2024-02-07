<?php
	$view['phar_included']=false;
	$view['admin_panel_loaded']=false;
	$view['middleware_form_loaded']=false;

	require 'phar://'
	.	'./tk.phar'
	.	'/com/admin_panel/admin_panel.php'
	;
	require 'phar://'
	.	'./tk.phar'
	.	'/com/middleware_form/middleware_form.php'
	;

	$phar_realpath='phar://'.realpath('./toolkit.phar');
	foreach(get_included_files() as $file)
		if(str_starts_with($file, strtr($phar_realpath, '\\', '/')))
		{
			$view['phar_included']=true;
			break;
		}

	if(in_array('admin_panel', get_declared_classes()))
		$view['admin_panel_loaded']=true;
	if(in_array('middleware_form', get_declared_classes()))
		$view['middleware_form_loaded']=true;

	try {
		new middleware_form();
	} catch(Throwable $error) {}

	$view['included_files']=var_export(get_included_files(), true);
	$view['declared_classes']=var_export(get_declared_classes(), true);
	$view['defined_functions']=var_export(get_defined_functions(), true);
?>