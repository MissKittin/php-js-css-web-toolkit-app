<?php
	require TK_LIB.'/check_var.php';
	require TK_LIB.'/sec_csrf.php';
	require TK_LIB.'/sec_login.php';

	$view['login_failed_single']=false;
	$view['login_failed_multi']=false;
	$view['login_failed_callback']=false;
	$view['logout']=false;

	function reload_page($view)
	{
		$view->view('login-library-test', 'reload_page.html');
	}

	if(csrf_check_token('post'))
	{
		if(login_single(
			check_post('user'),
			check_post('password'),
			$login_credentials_single[0],
			$login_credentials_single[1]
		)){
			login_refresh('callback', 'reload_page', [$view]);
			exit();
		}

		if(login_multi(
			check_post('user_multi'),
			check_post('password_multi'),
			$login_credentials_multi
		)){
			login_refresh('callback', 'reload_page', [$view]);
			exit();
		}

		if(login_callback(
			check_post('user_callback'),
			check_post('password_callback'),
			'callback_function'
		)){
			login_refresh('callback', 'reload_page', [$view]);
			exit();
		}

		if(check_post('logout') !== null)
		{
			logout();
			$view['logout']=true;

			login_refresh('callback', 'reload_page', [$view]);
			exit();
		}
	}
?>