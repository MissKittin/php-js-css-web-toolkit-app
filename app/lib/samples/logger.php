<?php
	/*
	 * Logging functions
	 *
	 * See:
	 *  app/src/controllers/samples/login-component-test.php
	 */

	if(!class_exists('log_to_txt'))
		require TK_LIB.'/logger.php';

	function log_fails()
	{
		static $logger=null;

		if($logger === null)
			$logger=new log_to_txt([
				'app_name'=>LOGGER_APP_NAME,
				'file'=>VAR_LOG.'/fails.log',
				'lock_file'=>VAR_LOG.'/fails.log.lock'
			]);

		return $logger;
	}
	function log_infos()
	{
		static $logger=null;

		if($logger === null)
			$logger=new log_to_txt([
				'app_name'=>LOGGER_APP_NAME,
				'file'=>VAR_LOG.'/infos.log',
				'lock_file'=>VAR_LOG.'/infos.log.lock'
			]);

		return $logger;
	}
?>