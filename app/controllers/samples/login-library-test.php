<?php
	require TK_LIB.'/check_var.php';
	require TK_LIB.'/sec_csrf.php';
	require TK_LIB.'/sec_login.php';

	$view['login_failed_single']=false;
	$view['login_failed_multi']=false;
	$view['login_failed_callback']=false;
	$view['logout']=false;

	$use_login_refresh=true;

	function reload_page($view)
	{
		$view->view(APP_VIEW.'/samples/login-library-test', 'reload_page.html');
	}

	if(csrf_check_token('post'))
	{
		if(login_single(
			check_post('user'),
			check_post('password'),
			$login_credentials_single[0],
			$login_credentials_single[1]
		))
			if($use_login_refresh)
			{
				login_refresh('callback', 'reload_page', [$view]);
				exit();
			}
		else
			$view['login_failed_single']=true;

		if(login_multi(
			check_post('user_multi'),
			check_post('password_multi'),
			$login_credentials_multi
		))
			if($use_login_refresh)
			{
				login_refresh('callback', 'reload_page', [$view]);
				exit();
			}
		else
			$view['login_failed_multi']=true;

		if(login_callback(
			check_post('user_callback'),
			check_post('password_callback'),
			'callback_function'
		))
			if($use_login_refresh)
			{
				login_refresh('callback', 'reload_page', [$view]);
				exit();
			}
		else
			$view['login_failed_callback']=true;

		if(logout(check_post('logout')))
		{
			$view['logout']=true;
			if($use_login_refresh)
			{
				login_refresh('callback', 'reload_page', [$view]);
				exit();
			}
		}
	}
?>