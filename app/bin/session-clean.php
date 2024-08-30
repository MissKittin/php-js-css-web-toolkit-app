<?php
	// Remove stale sessions

	require __DIR__.'/../lib/stdlib.php';
	require APP_LIB.'/app_session.php';

	try {
		app_session_clean(function($file){
			echo 'Removed '.$file.PHP_EOL;
		});
	} catch(app_session_exception $error) {
		echo $error->getMessage();
		exit(1);
	}
?>