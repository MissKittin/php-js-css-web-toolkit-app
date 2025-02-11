<?php
	// login_single or login_callback
		$login_method='login_single';

	// enable translation
		require TK_LIB.'/string_translator.php';

		$labels_lang='en';

		if(isset($_SERVER['HTTP_ACCEPT_LANGUAGE']))
			foreach(['pl', $labels_lang] as $labels_lang)
				if(str_starts_with($_SERVER['HTTP_ACCEPT_LANGUAGE'], $labels_lang))
					break;

		$labels=new string_translator(string_translator::from_json(
			file_get_contents(APP_VIEW.'/samples/login-component-test/labels.json'),
			$labels_lang
		));

	// set up credentials
		login_com_reg::_()['credentials']=$model::read_password('test'); // for login_single
		login_com_reg::_()['callback']=function($login) use($model) // for login_callback
		{
			return $model::read_password($login)[1];
		};

	// configure the login component
		(function($login_method, $array){
			login_com_reg_config::_()['method']=$login_method;

			foreach($array as $key=>$value)
				login_com_reg_view::_()[$key]=$value;
		})($login_method, [
			'lang'=>$labels_lang,
			'title'=>$labels('Login'),
			'login_style'=>'login_default_bright.css',
			'login_label'=>$labels('Username'),
			'password_label'=>$labels('Password'),
			'remember_me_label'=>$labels('Remember me'),
			'wrong_credentials_label'=>$labels('Wrong username or password'),
			'submit_button_label'=>$labels('Log in'),
			'loading_title'=>$labels('Loading...'),
			'loading_title'=>$labels('Loading...')
		]);

		if(app_env('APP_INLINE_ASSETS') === 'true')
			login_com_reg_view::_()['inline_style']=true;

		if(app_env('APP_MATERIALIZED') === 'true')
			login_com_reg_view::_()['template']='materialized';
		else if(
			isset($_COOKIE['app_dark_theme']) &&
			($_COOKIE['app_dark_theme'] === 'true') // from app/com/basic_template/assets/default.js/darkTheme.js
		)
			login_com_reg_view::_()['login_style']='login_default_dark.css';

	// add bruteforce protection
		$sec_bruteforce=null;

		if(!is_logged())
		{
			$sec_bruteforce=new bruteforce_timeout_pdo([
				'pdo_handle'=>pdo_instance()
			]);

			if($sec_bruteforce->check())
			{
				// disabled login prompt

				log_infos()->info(
					'IP '.$_SERVER['REMOTE_ADDR'].' is banned'
				);

				$_GET=[];
				$_POST=[];

				// remove this block to hide from the user any info that has been banned
				(function($array) use($labels){
					login_com_reg::_()['wrong_credentials']=true;
					login_com_reg_view::_()['wrong_credentials_label']=$labels(
						'You have been banned. Come back later.'
					);

					foreach($array as $key)
						login_com_reg_view::_()[$key]=true;
				})([
					'login_box_disabled',
					'password_box_disabled',
					'remember_me_box_disabled',
					'submit_button_disabled'
				]);

				login_com(); // display disabled login prompt

				return true; // exit()
			}
		}

	// define callbacks for the login component
		login_com_reg_config::_()['on_login_prompt']=function()
		{
			log_infos()->info(
				'Login prompt requested'
			);
		};
		login_com_reg_config::_()['on_login_success']=function()
		{
			log_infos()->info(
				'User logged in'
			);
		};
		login_com_reg_config::_()['on_login_failed']=function() use($sec_bruteforce)
		{
			log_fails()->info(
				$_SERVER['REMOTE_ADDR'].' login failed'
			);

			$sec_bruteforce->add();
		};
		login_com_reg_config::_()['on_logout']=function()
		{
			log_infos()->info(
				'User logged out'
			);
		};

	// display login prompt and exit
		if(login_com())
			return true; // exit()

	if(is_logged())
	{
		// credentials are valid, check if gd extension is installed for sec_captcha.php

		if(!extension_loaded('gd'))
		{
			log_fails()->warn(
				'gd extension not installed - CAPTCHA test disabled'
			);

			$_SESSION['captcha_verified']=true;
		}

		// if gd installed, do captcha now

		if(!isset($_SESSION['captcha_verified']))
		{
			require TK_LIB.'/sec_captcha.php';
			require TK_LIB.'/generate_csp_hash.php';

			if(
				(!isset($_POST['captcha'])) ||
				(!captcha_check($_POST['captcha']))
			){
				require TK_COM.'/middleware_form/main.php';

				if(app_env('APP_MATERIALIZED') === 'true')
					$captcha_form=new middleware_form('materialized');
				else
					$captcha_form=new middleware_form();

				if($captcha_form->is_form_sent())
				{
					login_com_reload(); // display reload page and exit
					return true; // exit()
				}

				$captcha_form_script=''
				.	'document.addEventListener(\'DOMContentLoaded\', function(){'
				.		file_get_contents(TK_LIB.'/disableEnterOnForm.js')
				.		file_get_contents(APP_VIEW.'/samples/login-component-test/middleware_form.js')
				.	'});';

				$captcha_form
				->	add_html_header('<script>'.$captcha_form_script.'</script>')
				->	add_csp_header('script-src', generate_csp_hash($captcha_form_script));

				unset($captcha_form_script);

				$captcha_form
				->	add_csp_header('img-src', 'data:')
				->	add_csp_header('style-src', '\'unsafe-hashes\'')
				->	add_csp_header('style-src', '\'sha256-N6tSydZ64AHCaOWfwKbUhxXx2fRFDxHOaL3e3CO7GPI=\'');

				if(app_env('APP_MATERIALIZED') !== 'true')
				{
					if(
						isset($_COOKIE['app_dark_theme']) &&
						($_COOKIE['app_dark_theme'] === 'true') // from app/com/basic_template/assets/default.js/darkTheme.js
					)
						$captcha_form->add_config('middleware_form_style', 'middleware_form_default_dark.css');
					else
						$captcha_form->add_config('middleware_form_style', 'middleware_form_default_bright.css');
				}

				$captcha_form
				->	add_config('title', $labels('Verification'))
				->	add_config('submit_button_label', $labels('Next'));

				if(app_env('APP_INLINE_ASSETS') === 'true')
					$captcha_form->add_config('inline_style', true);

				// now some magic: we place the generated image in $_SESSION
				// and in case of an incorrect answer we save CPU time,
				// and after giving the correct answer we delete it
				if(!isset($_SESSION['captcha_image']))
					$_SESSION['captcha_image']=base64_encode(
						captcha_get_once(
							new captcha_gd2()
						)
					);

				$captcha_form
				->	add_field([
						'tag'=>'img',
						'src'=>'data:image/jpeg;base64,'.$_SESSION['captcha_image'],
						'style'=>'width: 100%;'
					])
				->	add_field([
						'tag'=>'input',
						'type'=>'text',
						'name'=>'captcha',
						'placeholder'=>$labels('Enter the text from the image')
					])
				->	add_field([
						'tag'=>'input',
						'type'=>'slider',
						'slider_label'=>$labels('Show button'),
						'name'=>'i_am_bam'
					]);

				$captcha_form->view();

				return true; // exit()
			}

			$_SESSION['captcha_verified']=true;
			unset($_SESSION['captcha_image']);

			// display reload page and exit()
				login_com_reload();
				return true; // exit()
		}

		// captcha test passed, change password on first login

		// validate passwords
		function are_passwords_valid(
			$old_password,
			$new_password,
			$change_password_form,
			$labels
		){
			if($old_password === $new_password)
			{
				$change_password_form->add_error_message($labels(
					'The new password cannot be the same as the old one'
				));

				return false;
			}

			if(login_password_hash::password_verify(
				$new_password,
				login_com_reg::_()['credentials'][1]
			)){
				$change_password_form->add_error_message($labels(
					'The new password cannot be the same as the old one'
				));

				return false;
			}

			if(!login_password_hash::password_verify(
				$old_password,
				login_com_reg::_()['credentials'][1]
			)){
				$change_password_form->add_error_message($labels(
					'The old password is incorrect'
				));

				return false;
			}

			return true;
		}

		if($model::change_password_requested())
		{
			require TK_COM.'/middleware_form/main.php';

			if(app_env('APP_MATERIALIZED') === 'true')
				$change_password_form=new middleware_form('materialized');
			else
				$change_password_form=new middleware_form();

			if(
				$change_password_form->is_form_sent() &&
				isset($_POST['old_password']) && isset($_POST['new_password']) &&
				are_passwords_valid(
					$_POST['old_password'],
					$_POST['new_password'],
					$change_password_form,
					$labels
				)
			){
				$model::save_new_password(
					$_POST['new_password']
				);
				log_infos()->info(
					'Password updated'
				);

				// display reload page and exit
					login_com_reload();
					return true; // exit()
			}

			if(app_env('APP_MATERIALIZED') !== 'true')
			{
				if(
					isset($_COOKIE['app_dark_theme']) &&
					($_COOKIE['app_dark_theme'] === 'true') // from app/com/basic_template/assets/default.js/darkTheme.js
				)
					$change_password_form->add_config('middleware_form_style', 'middleware_form_default_dark.css');
				else
					$change_password_form->add_config('middleware_form_style', 'middleware_form_default_bright.css');
			}

			$change_password_form
			->	add_config('title', $labels('Password change'))
			->	add_config('submit_button_label', $labels('Change password'));

			if(app_env('APP_INLINE_ASSETS') === 'true')
				$change_password_form->add_config('inline_style', true);

			$change_password_form
			->	add_field([
					'tag'=>'input',
					'type'=>'password',
					'name'=>'old_password',
					'placeholder'=>$labels('Old password')
				])
			->	add_field([
					'tag'=>'input',
					'type'=>'password',
					'name'=>'new_password',
					'placeholder'=>$labels('New password')
				]);

			$change_password_form->view();

			return true; // exit()
		}

		// password updated, you can see the content (See: app/src/routes/samples/login-component-test.php)
	}

	return false;
?>