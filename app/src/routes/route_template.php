<?php
	require APP_LIB.'/app_template.php';
	//require APP_LIB.'/samples/ob_adapter.php';

	require APP_LIB.'/pdo_instance.php';
	//require APP_LIB.'/pdo_crud_builder.php';
	//require APP_LIB.'/pdo_cheat.php';

	require APP_CTRL.'/controller_template.php';
	require APP_MODEL.'/model_template.php';

	//ob_adapter
	//::	add(new ob_adapter_obminifier())
	//::	add(new ob_adapter_obsfucator('Page title', 'Enable js'))
	//::	add(new ob_adapter_gzip())
	//::	add(new ob_adapter_filecache(VAR_CACHE.'/ob_adapter/'.$output_file))
	//::	add(new ob_adapter_gunzip())
	//::	start();

	controller_name::main(
		new model_name(
			controller_name::model_params()
		),
		new app_template()
	)->view('view_template');

	//app_template::quick_view('view_template', 'page_content.html');
?>