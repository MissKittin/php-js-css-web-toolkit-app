<?php
	/*
	 * Logging functions
	 *
	 * See:
	 *  app/lib/samples/ob_cache.php
	 *  app/src/routes/samples/bootstrap-test.php
	 *  app/src/views/samples/bootstrap-test/template_config.php
	 *  app/src/controllers/samples/login-component-test.php
	 *  app/src/routes/samples/login-component-test.php
	 */

	if(!class_exists('log_to_txt'))
		require TK_LIB.'/logger.php';

	function log_fails(?string $app_name=null)
	{
		if($app_name !== null)
			return new log_to_txt([
				'app_name'=>$app_name,
				'file'=>VAR_LOG.'/fails.log',
				'lock_file'=>VAR_LOG.'/fails.log.lock'
			]);

		static $logger=null;

		if($logger === null)
			$logger=new log_to_txt([
				'app_name'=>LOGGER_APP_NAME,
				'file'=>VAR_LOG.'/fails.log',
				'lock_file'=>VAR_LOG.'/fails.log.lock'
			]);

		return $logger;
	}
	function log_infos(?string $app_name=null)
	{
		if($app_name !== null)
			return new log_to_txt([
				'app_name'=>$app_name,
				'file'=>VAR_LOG.'/infos.log',
				'lock_file'=>VAR_LOG.'/infos.log.lock'
			]);

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