<?php
	function http_error($error_code)
	{
		if(!class_exists('app_template'))
			require APP_LIB.'/app_template.php';

		if(!class_exists('ob_adapter'))
			require APP_LIB.'/ob_adapter.php';

		$lang='en';
		$theme='bright';
		$dark_theme=false;

		if(isset($_SERVER['HTTP_ACCEPT_LANGUAGE']))
			foreach(['pl', $lang] as $lang)
				if(str_starts_with($_SERVER['HTTP_ACCEPT_LANGUAGE'], $lang))
					break;

		// this cookie is from app/com/basic_template/assets/basic-template.js/appTheme.js
			if(
				isset($_COOKIE['app_theme']) &&
				($_COOKIE['app_theme'] === 'dark')
			){
				$theme='dark';
				$dark_theme=true;
			}

		if(ob_adapter
		::	add(new ob_adapter_obminifier())
		::	add(new ob_adapter_gzip())
		::	add(new ob_adapter_filecache(
				VAR_CACHE.'/ob_adapter/http_error_'.$error_code.'_'.$theme.'_'.$lang.'.cache',
				'ob_adapter'
			))
		::	add(new ob_adapter_gunzip())
		::	start()){
			app_template::finish_request();
			return;
		}

		require APP_COM.'/ie_error/main.php';

		ie_error($error_code, $lang, $dark_theme);
		app_template::finish_request();
	}
?>