<?php
	$login_credentials_single=['test', string2hash('test')];

	$login_credentials_multi=[
		'test'=>string2hash('test'),
		'test2'=>string2hash('test2')
	];

	function callback_function($login)
	{
		// also you can access database in this function

		switch($login)
		{
			case 'test':
				return string2hash('test');
			case 'test2':
				return string2hash('test2');
		}

		return null; // login failed
	}
?>