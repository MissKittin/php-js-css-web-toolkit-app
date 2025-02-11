<?php
	/*
	 * App input parameter manipulation library
	 *
	 * Functions:
	 *  app_params - cache for switch_path_info.php library
	 *  app_params_explode - wrapper that converts a string to an array
	 */

	class app_params_exception extends Exception {}

	function app_params()
	{
		/*
		 * Cache for switch_path_info.php library
		 *
		 * Required $_SERVER variables:
		 *  REQUEST_URI
		 *  SCRIPT_FILENAME
		 *  DOCUMENT_ROOT
		 *
		 * Warning:
		 *  switch_path_info.php library is required
		 *
		 * Note:
		 *  throws an app_params_exception on error
		 *
		 * Usage:
			switch(app_params())
			{
				case 'arg1/arg2/arg3':
					//
				break;
				case '':
					// home page
				break;
				default:
					// 404 Not found
			}
		 */

		static $cache=null;

		if($cache === null)
		{
			if(!function_exists('switch_path_info'))
				require TK_LIB.'/switch_path_info.php';

			foreach(['REQUEST_URI', 'SCRIPT_FILENAME', 'DOCUMENT_ROOT'] as $param)
				if(!isset($_SERVER[$param]))
					throw new app_params_exception(
						'$_SERVER["'.$param.'"] is not set'
					);

			$cache=switch_path_info(
				$_SERVER['REQUEST_URI'],
				$_SERVER['SCRIPT_FILENAME'],
				$_SERVER['DOCUMENT_ROOT']
			);
		}

		return $cache;
	}
	function app_params_explode(int $element)
	{
		/*
		 * An app_params() wrapper that converts a string to an array
		 *
		 * Note:
		 *  the conversion result will be cached
		 *  after the first use of the function
		 *
		 * Warning:
		 *  app_params function is required
		 *
		 * Usage:
			switch(app_params_explode(0))
			{
				case 'arg1': // eg: 'arg1/arg2/arg3' from app_params()
					if(app_params_explode(1) === 'arg2')
						// arg2 is defined (arg1/arg2)

					if(app_params_explode(2) === 'arg3')
						// arg3 is defined (arg1/???/arg3)
				break;
				case '':
					// home page
				break;
				default:
					// 404 Not found
			}
		 */

		static $cache=null;

		if($cache === null)
			$cache=explode('/', app_params());

		if(isset($cache[$element]))
			return $cache[$element];

		return '';
	}
?>