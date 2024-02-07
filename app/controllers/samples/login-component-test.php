<?php
	// replace long $array['a']['b']['c']='d' with nice array
	// because if you declare $array=['a'=>['b'=>['c'=>'d']]],
	// default settings will be lost
	function configure_login_component($params)
	{
		foreach($params as $section=>$options)
		{
			if(is_array($options))
				foreach($options as $option=>$value)
					$GLOBALS['_login'][$section][$option]=$value;
			else
				$GLOBALS['_login'][$section]=$options;
		}
	}

	// enable logging
	define('LOGGER_APP_NAME', 'login-component-test');
	require APP_LIB.'/samples/logger.php';

	$GLOBALS['_login']['credentials']=login_component_test_credentials::read_password(); // model

	// configure the login component
	configure_login_component([
		'config'=>[
			'method'=>'login_single'
		],
		'view'=>[
			'lang'=>'pl',
			'title'=>'Logowanie',
			'login_style'=>'login_bright.css',
			'login_label'=>'Nazwa użytkownika',
			'password_label'=>'Hasło',
			'remember_me_label'=>'Zapamiętaj mnie',
			'wrong_credentials_label'=>'Nieprawidłowa nazwa użytkownika lub hasło',
			'submit_button_label'=>'Zaloguj',
			'loading_title'=>'Ładowanie...',
			'loading_label'=>'Ładowanie...'
		]
	]);
	// this cookie is from app/templates/samples/default/assets/default.js/darkTheme.js
	if(
		isset($_COOKIE['app_dark_theme']) &&
		($_COOKIE['app_dark_theme'] === 'true')
	)
		$GLOBALS['_login']['view']['login_style']='login_dark.css';

	// add bruteforce protection
	require APP_LIB.'/samples/pdo_instance.php';
	require TK_LIB.'/sec_bruteforce.php';

	$sec_bruteforce=new bruteforce_timeout_pdo([
		'pdo_handler'=>pdo_instance()
	]);

	if($sec_bruteforce->check())
	{
		// disabled login prompt

		log_infos()->info('IP '.$_SERVER['REMOTE_ADDR'].' is banned');

		$_GET=[];
		$_POST=[];

		// remove this block to hide from the user any info that has been banned
		configure_login_component([
			'view'=>[
				'login_box_disabled'=>true,
				'password_box_disabled'=>true,
				'remember_me_box_disabled'=>true,
				'submit_button_disabled'=>true,
				'wrong_credentials_label'=>'Zostałeś zbanowany. Wróć później.'
			],
			'wrong_credentials'=>true
		]);

		require TK_COM.'/login/login.php';
		exit();
	}

	// define callbacks for the login component
	configure_login_component([
		'config'=>[
			'on_login_prompt'=>function(){
				log_infos()->info('Login prompt requested');
			},
			'on_login_success'=>function(){
				log_infos()->info('User logged in');
			},
			'on_login_failed'=>function() use($sec_bruteforce){
				log_fails()->info($_SERVER['REMOTE_ADDR'].' login failed');
				$sec_bruteforce->add();
			},
			'on_logout'=>function(){
				log_infos()->info('User logged out');
			}
		]
	]);

	// display login prompt
	require TK_COM.'/login/login.php';

	if(is_logged())
	{
		// credentials are valid, check if gd extension is installed for sec_captcha.php

		if(!extension_loaded('gd'))
		{
			log_fails()->warn('gd extension not installed - CAPTCHA test disabled');
			$_SESSION['captcha_verified']=true;
		}

		// if gd installed, do captcha now

		if(!isset($_SESSION['captcha_verified']))
		{
			require TK_LIB.'/sec_captcha.php';

			if((!isset($_POST['captcha'])) || (!captcha_check($_POST['captcha'])))
			{
				require TK_COM.'/middleware_form/middleware_form.php';
				$captcha_form=new middleware_form();

				$captcha_form
					->add_html_header(
						'<script>document.addEventListener(\'DOMContentLoaded\', function(){'
							.file_get_contents(TK_LIB.'/disableEnterOnForm.js')
							.file_get_contents(APP_VIEW.'/samples/login-component-test/middleware_form.js')
						.'})</script>'
					)
					->add_csp_header('script-src', '\'sha256-i7O9RlEhU3xgPLwptAzsYt/FTWOe7Q8NrYrH0zecJvk=\'');

				$captcha_form
					->add_csp_header('img-src', 'data:')
					->add_csp_header('style-src', '\'unsafe-hashes\'')
					->add_csp_header('style-src', '\'sha256-N6tSydZ64AHCaOWfwKbUhxXx2fRFDxHOaL3e3CO7GPI=\'');

				// this cookie is from app/templates/samples/default/assets/default.js/darkTheme.js
				if(
					isset($_COOKIE['app_dark_theme']) &&
					($_COOKIE['app_dark_theme'] === 'true')
				)
					$captcha_form->add_config('middleware_form_style', 'middleware_form_dark.css');
				else
					$captcha_form->add_config('middleware_form_style', 'middleware_form_bright.css');

				$captcha_form
					->add_config('title', 'Weryfikacja')
					->add_config('submit_button_label', 'Dalej');

				$captcha_form
					->add_field([
						'tag'=>'img',
						'src'=>'data:image/jpeg;base64,'.base64_encode(captcha_get_once('captcha_gd2')),
						'style'=>'width: 100%;'
					])
					->add_field([
						'tag'=>'input',
						'type'=>'text',
						'name'=>'captcha',
						'placeholder'=>'Przepisz tekst z obrazka'
					])
					->add_field([
						'tag'=>'input',
						'type'=>'slider',
						'slider_label'=>'Pokarz batona',
						'name'=>'i_am_bam'
					]);

				if($captcha_form->is_form_sent())
					require TK_COM.'/login/reload.php'; // display reload page
				else
					$captcha_form->view();

				exit();
			}

			$_SESSION['captcha_verified']=true;

			require TK_COM.'/login/reload.php'; // display reload page
			exit();
		}

		// captcha test passed, change password on first login

		// validate passwords
		function are_passwords_valid($old_password, $new_password, $change_password_form)
		{
			if($old_password === $new_password)
			{
				$change_password_form->add_error_message('Nowe hasło nie może być takie samo jak stare');
				return false;
			}

			if(password_verify($new_password, $GLOBALS['_login']['credentials'][1]))
			{
				$change_password_form->add_error_message('Nowe hasło nie może być takie samo jak stare');
				return false;
			}

			if(!password_verify($old_password, $GLOBALS['_login']['credentials'][1]))
			{
				$change_password_form->add_error_message('Stare hasło jest nieprawidłowe');
				return false;
			}

			return true;
		}

		if(login_component_test_credentials::change_password_requested())
		{
			require TK_COM.'/middleware_form/middleware_form.php';
			$change_password_form=new middleware_form();

			if(
				$change_password_form->is_form_sent() &&
				isset($_POST['old_password']) && isset($_POST['new_password']) &&
				are_passwords_valid(
					$_POST['old_password'],
					$_POST['new_password'],
					$change_password_form
				)
			){
				login_component_test_credentials::save_new_password($_POST['new_password']);
				log_infos()->info('Password updated');

				require TK_COM.'/login/reload.php'; // display reload page
				exit();
			}
			else
			{
				// this cookie is from app/templates/samples/default/assets/default.js/darkTheme.js
				if(
					isset($_COOKIE['app_dark_theme']) &&
					($_COOKIE['app_dark_theme'] === 'true')
				)
					$change_password_form->add_config('middleware_form_style', 'middleware_form_dark.css');
				else
					$change_password_form->add_config('middleware_form_style', 'middleware_form_bright.css');

				$change_password_form
					->add_config('title', 'Zmiana hasła')
					->add_config('submit_button_label', 'Zmień hasło');

				$change_password_form
					->add_field([
						'tag'=>'input',
						'type'=>'password',
						'name'=>'old_password',
						'placeholder'=>'Stare hasło'
					])
					->add_field([
						'tag'=>'input',
						'type'=>'password',
						'name'=>'new_password',
						'placeholder'=>'Nowe hasło'
					]);

					$change_password_form->view();
					exit();
			}
		}

		// password updated, you can see the content (see routes/samples/login-component-test.php)
	}
?>