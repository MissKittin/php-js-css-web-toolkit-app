<?php
	/*
	 * Modular output buffer
	 * I have such a fantasy
	 *
	 * Modules:
	 *  ob_adapter_filecache_mod - improved basic file cache
	 *
	 * See:
	 *  app/src/controllers/http_error.php
	 *  app/src/routes/samples/home.php
	 *  app/src/routes/samples/obsfucate-html.php
	 */

	require APP_LIB.'/ob_adapter.php';

	class ob_adapter_filecache_mod extends ob_adapter_filecache
	{
		public function __construct($output_file, $ob_adapter_class)
		{
			header('Cache-Control: public, max-age=31536000');
			parent::{__FUNCTION__}(
				VAR_CACHE.'/ob_adapter/'.$output_file,
				$ob_adapter_class
			);
		}
	}
?>