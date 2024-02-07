<?php
	require APP_CTRL.'/samples/http_error.php';

	$error_code=explode('/', strtok($_SERVER['REQUEST_URI'], '?'));

	if(isset($error_code[2]))
		switch($error_code[2])
		{
			case '401': require http_error(401); break;
			case '403': require http_error(403); break;
			//case '404': require http_error(404); break;
			case '410': require http_error(410); break;
			default: require http_error(400);
		}
	else
		require http_error(400);
?>