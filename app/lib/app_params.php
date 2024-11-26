<?php
	/*
	 * App input parameter manipulation library
	 *
	 * Functions:
	 *  app_params - $_SERVER['PATH_INFO'] replacement
	 *  app_params_explode - wrapper that converts a string to an array
	 */

	function app_params()
	{
		/*
		 * $_SERVER['PATH_INFO'] replacement
		 *
		 * If the application's public directory is in subdirectories,
		 * removes them from the path
		 *
		 * Note:
		 *  on cli-server if the file doesn't exist,
		 *  the $_SERVER['SCRIPT_FILENAME'] will contain
		 *  the path to the router file.
		 *  in this case the function won't work,
		 *  but this is not a bug - it's a feature
		 *
		 * Required $_SERVER variables:
		 *  REQUEST_URI
		 *  SCRIPT_FILENAME
		 *  DOCUMENT_ROOT
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

		if($cache !== null)
			return $cache;

		$request_uri=$_SERVER['REQUEST_URI'];
		$script_relative_path=strtr(substr(
			dirname($_SERVER['SCRIPT_FILENAME']),
			strlen($_SERVER['DOCUMENT_ROOT'])
		), '\\', '/');

		if(strcmp($script_relative_path, $request_uri) === 1)
			$i_len=strlen($request_uri);
		else
			$i_len=strlen($script_relative_path);

		for($i=0; $i<$i_len; ++$i)
		{
			if($script_relative_path[$i] === $request_uri[$i])
				continue;

			break;
		}

		if($i !== 0)
			$request_uri=substr($request_uri, $i);

		$cache=trim(strtok($request_uri, '?'), '/');

		return $cache;
	}
	function app_params_explode(int $element)
	{
		/*
		 * An app_params() wrapper that converts a string to an array
		 *
		 * Note:
		 *  the conversion result will be copied
		 *  after the first use of the function
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