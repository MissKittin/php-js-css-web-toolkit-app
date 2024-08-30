<?php
	require APP_CTRL.'/http_error.php';

	$error_code=explode('/', strtok($_SERVER['REQUEST_URI'], '?'));

	if(isset($error_code[2]))
	{
		$error_code[2]=filter_var($error_code[2], FILTER_VALIDATE_INT);

		switch($error_code[2])
		{
			case 401:
			case 403:
			//case 404:
			case 410:
				http_error($error_code[2]);
			break;
			default:
				http_error(400);
		}

		return;
	}

	http_error(400);
?>