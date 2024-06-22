<?php
	class ie_error_exception extends Exception {}
	function ie_error(int $error_code, string $lang='en', bool $dark_theme=false)
	{
		if(!file_exists(__DIR__.'/views/'.$lang))
			throw new ie_error_exception('Language "'.$lang.'" is not supported');

		$theme='bright';

		if($dark_theme)
			$theme='dark';

		if(!file_exists(__DIR__.'/views/'.$lang.'/'.$theme.'/'.$error_code.'.php'))
			throw new ie_error_exception('Error code "'.$error_code.'" is not supported');

		http_response_code($error_code);

		if(!isset($_SERVER['HTTP_HOST']))
			$_SERVER['HTTP_HOST']='';

		require __DIR__.'/views/'.$lang.'/'.$theme.'/'.$error_code.'.php';
	}
?>